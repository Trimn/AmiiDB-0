<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Staff;
use App\Models\Terms;
use App\Models\Action;
use App\Models\Gender;
use App\Models\People;
use App\Models\Status;
use App\Models\Actions;
use App\Models\Fellows;
use App\Models\Program;
use App\Models\Student;
use App\Models\Visitor;
use App\Models\Countries;
use App\Models\StaffType;
use App\Models\Immigration;
use App\Models\StudentAppt;
use App\Models\Supervisors;
use App\Tables\ApptMetrics;
use App\Tables\FellowStaff;
use App\Models\StaffSubtype;
use Illuminate\Http\Request;
use App\Tables\FellowStudents;
use App\Tables\StaffApptTable;
use App\Tables\StudentApptTable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\Splade\SpladeForm;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\Facades\Toast;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\StorePeopleRequest;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\File;
use App\Http\Requests\UpdatePeopleRequest;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;
use ProtoneMedia\Splade\FormBuilder\Checkbox;

class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('last_name', 'LIKE', "%{$value}%")
                        ->orWhere('first_name', 'LIKE', "%{$value}%")
                        ->orWhere('ccid', 'LIKE', "%{$value}%")
                        ->orWhere(DB::raw("CONCAT(`last_name`, ' ', `first_name`)"), 'LIKE', "%{$value}%")
                        ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', "%{$value}%");
                });
            });
        });
        
        $people = QueryBuilder::for(People::class)
            ->defaultSort('last_name')
            ->allowedSorts(['last_name', 'first_name', 'email', 'uid', 'ccid', 'gender', 'citizenship'])
            ->allowedFilters(['immigration', $globalSearch])
            ->paginate(request('perPage', 15))
            ->withQueryString();

        $immigrationOptions = Immigration::all()->pluck('code', 'code')->toArray();

        return view('people.index', [
            'people' => SpladeTable::for($people)
                ->defaultSort('last_name')
                ->withGlobalSearch()
                ->column('last_name', sortable: true)
                ->column('first_name', sortable: true)
                ->column('email', sortable: true)
                ->column('uid', 'University ID', sortable: true)
                ->column('ccid', sortable: true)
                ->column('gender', sortable: true)
                ->column('citizenship', sortable: true)
                ->column('immigration')
                ->column('amii_start', 'Amii Start Date')
                ->selectFilter('immigration', $immigrationOptions)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePeopleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePeopleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\People  $people
     * @return \Illuminate\Http\Response
     */
    public function show(People $person)
    {
        $fellow = Fellows::findFellow(Auth::user());
        if(Auth::user()->can('view') || (!is_null($fellow) && ($person->supervisor == $fellow->id || $person->supervisor2 == $fellow->name()))) {

        }
        else {
            Toast::danger('Unable to view person.');
            return redirect()->back();
        }
        $students = Student::where('pid', $person->id)->orderBy('created_at', 'desc')->get();
        foreach($students as &$s) {
            if($s->phd_post == 1) {
                $s->phd_post = true;
            }
        }
        $staffs = Staff::where('pid', $person->id)->with('pos_subtype')->get();
        $visitor = Visitor::where('pid', $person->id)->get()->first();
        $fellow = Fellows::where('pid', $person->id)->get()->first();
        $supervisors = $person->supervisors;

        $fellowForm = null;
        if($fellow) {
            $tmp = $fellow->toArray();
            $tmp['photo'] = null;
            $fellowForm = SpladeForm::make()
                ->action(route('fellows.update', $fellow))
                ->fields([
                    File::make('photo')->label('Photo')->accept(['image/png', 'image/jpeg', 'image/webp'])->filepond()->server()->preview(),
                    Input::make('last_name')->label('Last Name'),
                    Input::make('first_name')->label('First Name'),
                    // Select::make('gender')->options(Gender::options())->label('Gender'),
                    Input::make('ccid')->label('CCID'),
                    Input::make('title')->label('Title'),
                    Input::make('dept')->label('Dept'),
                    Input::make('report_id')->label('Reports to ID'),
                    Input::make('uid')->label('Supervisor ID'),
                    Input::make('email')->label('Alternate Email'),
                    // Input::make('assistant_name')->label('Assistant Name'),
                    // Input::make('assistant_email')->label('Assistant Email'),
                    Date::make('start')->label('Fellow Start Date'),
                    Input::make('website')->label('Website'),
                    Input::make('committees')->label('Committees'),
                    Input::make('office')->label('Office #'),
                    Input::make('phone')->label('Phone #'),
                    Input::make('alias')->label('RHP Export name'),
                    Input::make('pub_platform_primary')->label('Main Publication Platform'),
                    Input::make('pub_list_location')->label('Publication List Location'),
                    Checkbox::make('temporary_id')
                        ->label('Temporary ID'),
                    Checkbox::make('ccai_chair')
                        ->label('CCAI Chair'),
                    Date::make('ccai_start')->label('CCAI Start Date'),
                    Date::make('ccai_end')->label('CCAI End Date'),
                    Textarea::make('notes')->label('Notes'),
                    Submit::make('submit')->label('Update'),
                ])
                ->fill(array_merge($person->toArray(), $tmp));
        }

        // dd(Fellows::options());

        return view('people.show', [
            'person' => $person,
            'students' => $students,
            'staffs' => $staffs,
            'visitor' => $visitor,
            'fellow' => $fellow,
            'fellowForm' => $fellowForm,
            'supervisors' => $supervisors,
            'fellowStudents' => new FellowStudents($fellow),
            'fellowStaff' => new FellowStaff($fellow),
            'genders' => Gender::options(),
            'programs' => Program::options(),
            'terms' => Terms::options(),
            'statuses' => Status::options(),
            'immigrations' => Immigration::options(),
            'countries' => Countries::options(),
            'posTypes' => StaffType::options(),
            'subtypes' => StaffSubtype::options(),
            'fellows' => Fellows::options(),
            'edit' => Auth::user()->can('edit'),
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\People  $people
     * @return \Illuminate\Http\Response
     */
    public function edit(People $people)
    {
        //
    }

    public function find(Request $request) {
        $person = People::where('uid', $request->uid)->get()->first();
        if(!is_null($person)) {
            return response()->json($person->toArray());
        }
        return response()->json(array());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePeopleRequest  $request
     * @param  \App\Models\People  $people
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePeopleRequest $request, People $person)
    {
        // $before = $person->toJson();
        $input = $request->validated();
        
        // Check if post_uofa_employer field has changed
        if (isset($input['post_uofa_employer']) && $input['post_uofa_employer'] !== $person->post_uofa_employer) {
            $input['post_uofa_employer_updated_at'] = now();
        }
        
        $person->fill($input);
        $person->save();
        // $after = $person->toJson();

        // $action = new Action([
        //     'user' => $request->user()->name,
        //     'table' => 'people',
        //     'from' => $before,
        //     'to' => $after
        // ]);
        // $action->save();

        Toast::title('Updated Successfully!')
            ->message('Successfully updated user')
            ->autoDismiss(5);

        return $this->show($person);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\People  $people
     * @return \Illuminate\Http\Response
     */
    public function destroy(People $people)
    {
        //
    }

    public function apptMetrics(Request $request) {
        $start = $request->query('start', Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now()->subYear(1)->year . '-03-01 00:00:01')->format('Y-m-d'));
        $end = $request->query('end', Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now()->year . '-03-01 00:00:00')->subSeconds(2)->format('Y-m-d'));

        return view('metrics.appts', [
            'appts' => ApptMetrics::class,
            'start' => $start,
            'end' => $end,
        ]);
    }

    public function getRandomUID()
    {
        // Generate a random UID
        $uid = '7' . mt_rand(1000000, 9999999);

        // Check if the UID already exists in the People table
        while (People::where('uid', $uid)->exists()) {
            $uid = '7' . mt_rand(1000000, 9999999);;
        }

        return response()->json(['uid' => $uid]);
    }

    public function missingSalaryPeople(Request $request)
    {
        $showIgnored = $request->boolean('show_ignored');

        return view('people.missing-salary-people', [
            'table' => new \App\Tables\MissingSalaryPeopleTable($showIgnored),
            'showIgnored' => $showIgnored,
        ]);
    }

    public function missingSalaryTransactions($uid, $project, $program)
    {
        $notes = \App\Models\MissingPeopleNotes::firstOrNew(['uid' => str_pad($uid, 7, '0', STR_PAD_LEFT)]);

        return view('people.missing-salary-transactions', [
            'uid' => $uid,
            'project' => $project,
            'program' => $program,
            'notes' => $notes,
            'projectTeamOptions' => \App\Models\ProjectTeam::options(),
            'table' => new \App\Tables\MissingSalaryTransactionsTable($uid, $project, $program),
        ]);
    }

    public function showAddMissingPersonForm(Request $request)
    {
        $uid = $request->input('uid', '');
        $importName = trim((string) $request->input('name', ''));

        $lastName = '';
        $firstName = '';

        if ($importName !== '') {
            $commaPos = strpos($importName, ',');
            if ($commaPos !== false) {
                $lastName = trim(substr($importName, 0, $commaPos));
                $firstName = trim(substr($importName, $commaPos + 1));
            } else {
                $lastName = $importName;
            }
        }

        return view('people.add-missing-person', compact('uid', 'lastName', 'firstName', 'importName'));
    }

    public function addMissingPerson(Request $request)
    {
        $uid = $request->input('uid');
        $personType = $request->input('person_type');

        if (!$uid) {
            Toast::title('UID is required')->danger()->autoDismiss(30);
            return redirect()->back();
        }

        if (!in_array($personType, ['student', 'staff'])) {
            Toast::title('Please select Student or Staff')->danger()->autoDismiss(30);
            return redirect()->back();
        }

        $person = People::where('uid', $uid)->first();
        if (!$person) {
            $person = new People();
        }

        $person->uid = $uid;
        $person->last_name = $request->input('last_name', '');
        $person->first_name = $request->input('first_name', '');
        $person->email = $request->input('email');
        $person->ccid = $request->input('ccid');
        $person->gender = $request->input('gender');
        $person->citizenship = $request->input('citizenship');
        $person->immigration = $request->input('immigration');
        $person->amii_start = $request->input('amii_start');
        $person->supervisor = $request->input('supervisor');
        $person->supervisor2 = $request->input('supervisor2');
        $person->wp_type = $request->input('wp_type');
        $person->wp_start = $request->input('wp_start');
        $person->wp_end = $request->input('wp_end');
        $person->save();

        if ($personType === 'student') {
            Student::updateOrCreate(
                ['pid' => $person->id],
                [
                    'program' => $request->input('program'),
                    'phd_post' => $request->input('phd_post', false),
                    'program_start' => $request->input('program_start'),
                    'dept' => $request->input('dept'),
                    'curr_step' => $request->input('curr_step'),
                    'term_adj' => $request->input('term_adj'),
                    'gf_last' => $request->input('gf_last'),
                    'convocation' => $request->input('convocation'),
                    'active' => $request->input('active'),
                    'notes' => $request->input('notes'),
                ]
            );
        } else {
            Staff::updateOrCreate(
                ['pid' => $person->id],
                [
                    'job_title' => $request->input('job_title'),
                    'dept' => $request->input('dept'),
                    'pos_type' => $request->input('pos_type'),
                    'subtype' => $request->input('subtype'),
                    'pdf_completed' => $request->input('pdf_completed'),
                    'active' => $request->input('active'),
                    'notes' => $request->input('notes'),
                ]
            );
        }

        Toast::title('Person created as ' . ucfirst($personType) . ' successfully')->success()->autoDismiss(30);
        return redirect()->route('people.missing_salary');
    }

    public function ignoreMissingPerson(Request $request)
    {
        $uid = $request->input('uid');
        if (!$uid) {
            Toast::title('UID is required')->danger()->autoDismiss(30);
            return redirect()->back();
        }

        $note = \App\Models\MissingPeopleNotes::firstOrCreate(['uid' => $uid]);
        $note->ignored = true;
        $note->save();

        Toast::title('Person ignored')->success()->autoDismiss(30);
        return redirect()->back();
    }

    public function unignoreMissingPerson(Request $request)
    {
        $uid = $request->input('uid');
        if (!$uid) {
            Toast::title('UID is required')->danger()->autoDismiss(30);
            return redirect()->back();
        }

        $note = \App\Models\MissingPeopleNotes::firstOrCreate(['uid' => $uid]);
        $note->ignored = false;
        $note->save();

        Toast::title('Person unignored')->success()->autoDismiss(30);
        return redirect()->back();
    }

    public function updateMissingNotes(Request $request)
    {
        $uid = $request->input('uid');
        if (!$uid) {
            return redirect()->back();
        }

        $note = \App\Models\MissingPeopleNotes::firstOrCreate(['uid' => $uid]);
        if ($request->has('notes')) {
            $note->notes = $request->input('notes');
        }
        if ($request->has('who_id')) {
            $whoId = $request->input('who_id');
            $note->who_id = ($whoId === 'NULL' || $whoId === '') ? null : $whoId;
        }
        $note->save();

        return redirect()->back();
    }
}
