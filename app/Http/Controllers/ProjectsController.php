<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Etrac;
use App\Models\Fellows;
use App\Models\Project;
use App\Models\EtracRaw;
use App\Models\Projects;
use App\Models\Speedcodes;
use App\Forms\ProjectsForm;
use App\Models\FellowsView;
use App\Models\NewProjects;
use App\Imports\EtracImport;
use Illuminate\Http\Request;
use App\Imports\SalaryImport;
use App\Tables\ProjectsTable;
use App\Imports\ProjectImport;
use App\Models\ProjectImports;
use Illuminate\Support\Number;
use App\Models\MissingProjects;
use App\Forms\CreateProjectForm;
use App\Jobs\ProcessEtracImport;
use App\Tables\ForecastingStaff;
use App\Tables\ForecastingTable;
use Illuminate\Support\Facades\DB;
use App\Tables\ForecastingStudents;
use App\Tables\ProjectBudgetsTable;
use Maatwebsite\Excel\Facades\Excel;
use ProtoneMedia\Splade\Facades\Toast;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProjectsRequest;
use ProtoneMedia\Splade\FormBuilder\Submit;
use App\Http\Requests\UpdateProjectsRequest;
use App\Jobs\ProcessSalaryImport;
use App\Models\SalaryRaw;
use App\View\Components\NewProject;
use EllGreen\LaravelLoadFile\Laravel\Facades\LoadFile;
use ProtoneMedia\Splade\FileUploads\HandleSpladeFileUploads;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $table = ProjectsTable::build();
        // dd($table);
        return view('projects.index', [
            'table' => $table,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $form = CreateProjectForm::make()
            ->fields([
                Submit::make()->label('Add')
            ]);
        return view('projects.create', [
            'form' => $form,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectsRequest $request)
    {
        $input = $request->validated();
        if(Projects::where('code', $input['code'])->first()) {
            Toast::title('Project ' . $input['code'] . ' already exists!')->danger();
            return redirect()->back();
        }
        if(!isset($input['project_status'])) {
            $input['project_status'] = '';
        }
        $project = new Projects($input);
        $project->save();

        $sc = Speedcodes::find($input['code']);
        $sc->fill($input);
        $sc->save();

        return redirect()->route('projects');
    }

    public function import(Request $request) {
        HandleSpladeFileUploads::forRequest($request);

        $import = new ProjectImport();
        $file = $request->file;
        $content = $file->get();

        $speedcodes = Speedcodes::all();
        
        if(str_contains(strtolower(substr($content, 0, 100)), 'html')) {
            //$import->import($request->file, null, \Maatwebsite\Excel\Excel::HTML);
            $collection = Excel::toCollection($import, $request->file, null, \Maatwebsite\Excel\Excel::HTML);
        }
        else {
            //$import->import($request->file);
            $collection = Excel::toCollection($import, $request->file);
        }

        // The PeopleSoft HTML export is malformed (no </tr> close tags, <meta> outside <head>).
        // Different libxml2 versions on Linux vs Windows parse this differently, sometimes
        // inserting extra leading rows. When that happens, WithHeadingRow reads the wrong row
        // as headings and rows get numeric keys instead of named ones (e.g. 'speed_code').
        // Drop any leading rows that aren't keyed by column name before processing.
        $rows = $collection[0]->filter(fn($row) => $row->has('speed_code'));

        $missing = $speedcodes->whereNotIn('project', $rows->pluck('project_id'));
        foreach($missing as $item) {
            $proj = Projects::where('code', $item['code'])->first();
            if($proj && !str_starts_with($proj['project_status'], 'Complete')) {
                MissingProjects::upsert(['code' => $item['code']], uniqueBy: 'code');
            }
        }

        // dd($collection[0][848]);
        $new = collect();
        $fellows = Fellows::optionsAliases();
        foreach($rows as $row) {
            $data = [
                'code' => $row['speed_code'],
                'total_award' => Projectimport::toMoney($row['total_award']),
                'funds_before' => Projectimport::toMoney($row['funds_available_before_commitments']),
                'funds_after' => Projectimport::toMoney($row['funds_available_after_commitments']),
                'project_status' => $row['project_status'] ?? '',
                'percent_spent' => $row['percent_spent'],
                'oe_status' => $row['over_expenditure_status'],
                'auth_oe_amount' => Projectimport::toMoney($row['authorized_oe_amount']),
                //'oe_auth_end' => Carbon::parse($row['oe_authorization_end_date'])->toDateString(),
                'oe_auth_end' => $row['oe_authorization_end_date'] ? Carbon::parse($row['oe_authorization_end_date'])->toDateString() : null,
                'oe_req_status' => $row['oe_request_status'],
            ];
            if(is_null($row['speed_code'])) {
                $sc = Speedcodes::where('project', '=', $row['project_id'])->first();
                if($sc) {
                    $data['code'] = $sc->code;
                }
            }
            else {
                $sc = Speedcodes::find($row['speed_code']);
            }
            if($sc) {
                $sc->award_start = $row['award_begin_date'] ? Carbon::parse($row['award_begin_date'])->toDateString() : null;
                $sc->award_end = $row['award_end_date'] ? Carbon::parse($row['award_end_date'])->toDateString() : null;
                $sc->description = $row['title'];
                $sc->save();

                Projects::upsert($data, ['code']);
            }
            elseif(in_array($row['holder'], $fellows) && !is_null($row['speed_code']) && strlen($row['speed_code']) == 5) {
                $new->push($row);
                $proj = array_merge([
                    'holder' => $row['holder'],
                    'project_id' => $row['project_id'],
                    'award_start' => Carbon::parse($row['award_begin_date'])->toDateString(),
                    'award_end' => Carbon::parse($row['award_end_date'])->toDateString(),
                    'title' => $row['title'],
                ], $data);

                NewProjects::upsert($proj, ['code']);
            }
        }

        // dd([$fellows, $new, $collection[0]->pluck('holder')->unique()]);

        $failures = [];
        foreach ($import->failures() as $failure) {
            $row = $failure->row(); // row that went wrong
            $attrib = $failure->attribute(); // either heading key (if using heading row concern) or column index
            $errors = $failure->errors(); // Actual error messages from Laravel validator
            $values = $failure->values(); // The values of the row that has failed.
            array_push($failures, ['row' => $row, 'attrib' => $attrib, 'errors' => $errors, 'values' => $values]);
        }

        $log = new ProjectImports([
            'errors' => json_encode($failures),
        ]);
        $log->save();

        return redirect()->route('projects');
    }

    public function importEtrac(Request $request) {
        HandleSpladeFileUploads::forRequest($request);

        $import = new EtracImport();
        $file = $request->file;
        if (!$file) {
            Toast::title('No file uploaded')->danger();
            return redirect()->back();
        }
        $curr_file = $request->file;
        $inpath = null;
        $content = $file->get();

        EtracRaw::truncate();

        $outpath = null;
        if(str_contains(strtolower(substr($content, 0, 100)), 'html')) {
            // $job = Excel::import($import, $request->file, null, \Maatwebsite\Excel\Excel::HTML);
            $infile = $file->store();
            $inpath = Storage::path($infile);
            $newpath = str_replace('xls', 'html', $inpath);
            rename($inpath, $newpath);
            $inpath = $newpath;
            $outfile = uniqid() . '.csv';
            $outpath = Storage::path($outfile);
            $curr_file = $outpath;
            $cmd = 'pyexcel transcode ' . $inpath . ' ' . $outpath . ' 2>&1';
            $out = shell_exec($cmd);
            // $job = $import->import($curr_file);
            
            // $collection = clock(Excel::toCollection($import, $request->file, null, \Maatwebsite\Excel\Excel::HTML));
        }
        else {
            if(str_ends_with($file->getClientOriginalName(), 'xlsx') || str_ends_with($file->getClientOriginalName(), 'xls')) {
                $infile = $file->store();
                $inpath = Storage::path($infile);
                $outfile = uniqid() . '.csv';
                $outpath = Storage::path($outfile);
                $curr_file = $outpath;
                // $cmd = 'echo $PATH';
                $cmd = 'pyexcel transcode ' . $inpath . ' ' . $outpath . ' 2>&1';
                $out = shell_exec($cmd);
                // dd([$cmd, $out]);
            }
            else {
                // For non-xlsx/xls files, use the file directly
                $infile = $file->store();
                $inpath = Storage::path($infile);
                $outfile = uniqid() . '.csv';
                $outpath = Storage::path($outfile);
                $curr_file = $outpath;
            }
            // $job = Excel::import($import, $curr_file);
            // $job = $import->import($curr_file);
            // $collection = clock(Excel::toCollection($import, $request->file));
        }
        // $job->chain([new ProcessEtracImport($curr_file, $inpath)]);
        // $job->dispatch();

        // $job = new ProcessEtracImport($outpath, $inpath);
        // $job->dispatch();
        if ($outpath && $inpath) {
            ProcessEtracImport::dispatch($outpath, $inpath);
        } else {
            Toast::title('Unable to process file')->danger();
        }

        return redirect()->back();
    }

    public function importSalary(Request $request) {
        HandleSpladeFileUploads::forRequest($request);

        $import = new SalaryImport();
        $file = $request->file;
        if (!$file) {
            Toast::title('No file uploaded')->danger();
            return redirect()->back();
        }
        $curr_file = $request->file;
        $inpath = null;
        $content = $file->get();

        SalaryRaw::truncate();

        $outpath = null;
        if(str_contains(strtolower(substr($content, 0, 100)), 'html')) {
            // $job = Excel::import($import, $request->file, null, \Maatwebsite\Excel\Excel::HTML);
            $infile = $file->store();
            $inpath = Storage::path($infile);
            $newpath = str_replace('xls', 'html', $inpath);
            rename($inpath, $newpath);
            $inpath = $newpath;
            $outfile = uniqid() . '.csv';
            $outpath = Storage::path($outfile);
            $curr_file = $outpath;
            // $job = $import->import($curr_file);
            // $collection = clock(Excel::toCollection($import, $request->file, null, \Maatwebsite\Excel\Excel::HTML));
        }
        else {
            if(str_ends_with($file->getClientOriginalName(), 'xlsx') || str_ends_with($file->getClientOriginalName(), 'xls')) {
                $infile = $file->store();
                $inpath = Storage::path($infile);
                $outfile = uniqid() . '.csv';
                $outpath = Storage::path($outfile);
                $curr_file = $outpath;
                // $cmd = 'echo $PATH';
                
                // dd([$cmd, $out]);
            }
            else {
                // For non-xlsx/xls files, use the file directly
                $infile = $file->store();
                $inpath = Storage::path($infile);
                $outfile = uniqid() . '.csv';
                $outpath = Storage::path($outfile);
                $curr_file = $outpath;
            }
            // $job = Excel::import($import, $curr_file);
            // $job = $import->import($curr_file);
            // $collection = clock(Excel::toCollection($import, $request->file));
        }

        if ($inpath && $outpath) {
            $cmd = 'pyexcel transcode --csv-output-lineterminator "|" --csv-output-quoting all ' . $inpath . ' ' . $outpath . ' 2>&1';
            $out = shell_exec($cmd);

            if (!file_exists($outpath)) {
                Log::error('pyexcel transcode failed', ['command' => $cmd, 'output' => $out]);
                Toast::title('File conversion failed — is pyexcel installed?')->danger();
                return redirect()->back();
            }

            ProcessSalaryImport::dispatch($curr_file, $inpath);
        } else {
            Toast::title('Unable to process file')->danger();
        }
        return redirect()->back();
    }

    public function add(NewProjects $project) {
        $fellow = FellowsView::query()
            ->where('alias', '=', $project->holder)
            ->orWhere('name', '=', $project->holder)
            ->first();

        $sc = Speedcodes::where('code', '=', $project->code)->first();
        if(is_null($sc)) {
            $sc = new Speedcodes([
                'code' => $project->code,
                'fellow' => $fellow->id,
                'description' => $project->title,
                'project' => $project->project_id,
                'award_start' => $project->award_start,
                'award_end' => $project->award_end,
                'status' => 'Pending',
            ]);
            $sc->save();
        }

        $proj = new Projects($project->toArray());
        $proj->save();

        NewProjects::where('code' , '=', $project->code)->delete();

        return redirect()->back();
    }

    public function ignore(NewProjects $project) {
        $project->delete();

        return redirect()->back();
    }

    public function complete(MissingProjects $project) {
        $proj = Projects::where('code', $project['code'])->first();
        $proj['project_status'] = 'Complete';
        $proj->save();
        $project->delete();

        return redirect()->back();
    }

    public function ignoreMissing(MissingProjects $project) {
        $project->delete();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Projects $projects)
    {
        //
    }

    private function formatCurrency($item) {
        return is_null($item) ? $item : Number::currency($item);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Projects $projects)
    {
        $data = array_merge(Speedcodes::find($projects->code)->toArray(), $projects->toArray());
        $data['combo_code'] = $data['combo_code'] ? str_pad($data['combo_code'], 9, '0', STR_PAD_LEFT) : $data['combo_code'];
        $data['total_award'] = $this->formatCurrency($data['total_award']);
        $data['funds_before'] = $this->formatCurrency($data['funds_before']);
        $data['funds_after'] = $this->formatCurrency($data['funds_after']);
        $data['auth_oe_amount'] = $this->formatCurrency($data['auth_oe_amount']);
        $data['future_funding'] = $this->formatCurrency($data['future_funding']);
        $data['percent_spent'] = is_null($data['percent_spent']) ? $data['percent_spent'] : Number::percentage($data['percent_spent'], 2);
        $form = CreateProjectForm::make()
            ->action(route('projects.update', $projects))
            ->fields([
                Submit::make()->label('Update')
            ])
            ->fill($data);
        return view('projects.edit', [
            'form' => $form,
        ]);
    }

    public function editForecasting(Projects $projects, $end)
    {
        $data = array_merge(Speedcodes::find($projects->code)->toArray(), $projects->toArray());
        $data['combo_code'] = $data['combo_code'] ? str_pad($data['combo_code'], 9, '0', STR_PAD_LEFT) : $data['combo_code'];
        $data['total_award'] = $this->formatCurrency($data['total_award']);
        $data['funds_before'] = $this->formatCurrency($data['funds_before']);
        $data['funds_after'] = $this->formatCurrency($data['funds_after']);
        $data['auth_oe_amount'] = $this->formatCurrency($data['auth_oe_amount']);
        $data['future_funding'] = $this->formatCurrency($data['future_funding']);
        $data['percent_spent'] = is_null($data['percent_spent']) ? $data['percent_spent'] : Number::percentage($data['percent_spent'], 2);
        $form = CreateProjectForm::make()
            ->action(route('projects.update', $projects))
            ->fields([
                Submit::make()->label('Update')
            ])
            ->fill($data);
        $students = new ForecastingStudents($projects, $end);
        $staff = new ForecastingStaff($projects, $end);
        $budgets = new ProjectBudgetsTable($projects);
        return view('forecasting.edit', [
            'form' => $form,
            'students' => $students,
            'staff' => $staff,
            'budgets' => $budgets,
            'project' => $projects,
            'est_students' => Projects::student_commitment($projects, $end),
            'est_staff' => Projects::staff_commitment($projects, $end),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectsRequest $request, Projects $projects)
    {
        $input = $request->validated();
        $projects->fill($input);
        $projects->save();

        $sc = Speedcodes::find($input['code']);
        $sc->fill($input);
        // Ensure status is set to empty string if not provided or null
        if (!isset($input['status']) || is_null($input['status'])) {
            $sc->status = '';
        }
        $sc->save();

        return redirect()->back();
    }

    public function checkboxUpdate(Request $request, Projects $project)
    {
        if($request->has('ff_verified')) {
            $project->ff_verified = $request->ff_verified;
        }
        if($request->has('financial_report')) {
            $project->financial_report = $request->financial_report;
        }
        if($request->has('supervisor_review')) {
            $project->supervisor_review = $request->supervisor_review;
        }
        $project->save();

        return redirect()->back();
    }

    public function forecasting() {
        $now = Carbon::now();
        $fy_curr = Carbon::createFromFormat('Y-m-d H:s:i', $now->year . '-04-01 00:00:01');
        $fy_prev = Carbon::createFromFormat('Y-m-d H:s:i', ($now->year-1) . '-04-01 00:00:01');
        $year = ($now->lte($fy_curr) ? $fy_prev : $fy_curr)->year;
        $start = request()->query('start', Carbon::createFromFormat('Y-m-d H:s:i', $year . '-04-01 00:00:01')->format('Y-m-d'));
        $end = request()->query('end', Carbon::createFromFormat('Y-m-d H:s:i', ($year+1) . '-04-01 00:00:00')->subSeconds(2)->format('Y-m-d'));

        return view('forecasting.index', [
            'table' => ForecastingTable::build(),
            'start' => $start,
            'end' => $end,
            'fy' => $now->year,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Projects $projects)
    {
        //
    }

}
