<?php

namespace App\Http\Controllers;

use App\Models\Terms;
use App\Models\Gender;
use App\Models\People;
use App\Models\Status;
use App\Models\Program;
use App\Models\Student;
use App\Models\Countries;
use App\Models\Immigration;
use App\Models\Supervisors;
use App\Tables\StudentTable;
use Illuminate\Http\Request;
use App\Forms\CreateStudentForm;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\Facades\Toast;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\PeopleController;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Requests\CreateStudentFormRequest;
use App\Http\Requests\CreateStudentRecordRequest;
use App\Models\RatesView;
use App\Models\StudentAppt;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('students.index', [
            'students' => StudentTable::class
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
        return view('students.create', [
            'form' => CreateStudentForm::class
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStudentFormRequest $request)
    {
        //
        $input = $request->validated();
        if($input['program'] != 'PhD') {
            $input['phd_post'] = null;
        }

        $person = People::updateOrCreate(
            [
                'last_name' => $input['last_name'],
                'first_name' => $input['first_name'],
                'email' => $input['email'],
                'uid' => $input['uid'],
                'ccid' => $input['ccid'],
                'gender' => $input['gender'],
                'citizenship' => $input['citizenship'],
                'immigration' => $input['immigration'],
                'amii_start' => $input['amii_start'],
                'supervisor' => $input['supervisor'],
                'supervisor2' => $input['supervisor2'],
                'wp_type' => $input['wp_type'],
                'wp_start' => $input['wp_start'],
                'wp_end' => $input['wp_end'],
            ]
        );

        $student = Student::updateOrCreate(
            [
                'pid' => $person->id,
                'program' => $input['program'],
                'program_start' => $input['program_start'],
                'dept' => $input['dept'],
                'phd_post' => $input['phd_post'],
                'phd_post_checked' => $input['phd_post'] ? Carbon::now()->format('Y-m-d') : null,
                'curr_step' => $input['curr_step'],
                'term_adj' => $input['term_adj'],
                'gf_last' => $input['gf_last'],
                'convocation' => $input['convocation'] ?? null,
                'active' => $input['active'],
                'notes' => $input['notes'],
            ]
        );

        Toast::title('Created Successfully!')
            ->message('Successfully created student')
            ->autoDismiss(5);

        return redirect()->back();
    }

    public function createRecord(People $person) {
        return view('students.createrec', [
            'person' => $person
        ]);
    }

    public function storeRecord(CreateStudentRecordRequest $request, People $person) {
        $input = $request->validated();
        Student::updateOrCreate([
            'pid' => $person->id,
            'program' => $input['program'],
            'program_start' => $input['program_start'],
            'dept' => $input['dept'],
            'phd_post' => $input['phd_post'],
            'phd_post_checked' => $input['phd_post'] ? Carbon::now()->format('Y-m-d') : null,
            'curr_step' => $input['curr_step'],
            'term_adj' => $input['term_adj'],
            'gf_last' => $input['gf_last'],
            'convocation' => $input['convocation'] ?? null,
            'active' => $input['active'],
            'notes' => $input['notes'],
        ]);

        $person->touch();

        return redirect()->route('people.show', [
            'person' => $person
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /*
     * Find student from search parameters
     * @return Array(\App\Models\Student)
     */
    public function apiSearch(Request $request)
    {
        $needle = $request->query('query');
        $start = $request->query('start');
        $end = $request->query('end');
        $speedcode = $request->query('speedcode');

        $students = Student::query()->whereHas('person', function (Builder $query) use ($needle) {
            $query
                ->where('uid', '=', $needle)
                ->orWhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', '%'.$needle.'%');
        })
        ->with(['person', 'person._supervisor', 'appt', 'appt.appt', 'appt.rate_view'])
        ->get();

        $res = [];

        foreach($students as $student) {
            $tmp = $student->toArray();
            $appt = $student->appt;
            if($start) {
                $appt = $appt->where('end', '>', $start);
            }
            if($end) {
                $appt = $appt->where('start', '<', $end);
            }
            if($speedcode) {
                $appt = $appt->filter(function ($item) use ($speedcode) {
                    return $item->speedcode_1 == $speedcode || $item->speedcode_2 == $speedcode || $item->speedcode_3 == $speedcode;
                });
            }
            $tmp['appt'] = $appt;
            array_push($res, $tmp);
        }
        // return response()->json($students->toSql());
        return $res;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        $studentCombined = QueryBuilder::for(Student::class)
            ->join('people', 'student.pid', '=', 'people.id')
            ->select('student.*', 'people.last_name as last_name', 'people.first_name as first_name', 'people.email as email', 'people.uid as uid', 'people.ccid as ccid', 'people.gender as gender', 'people.citizenship as citizenship', 'people.immigration as immigration', 'people.amii_start as amii_start')
            ->where('student.id', '=', $student->id)
            ->get()
            ->first();

        return view('students.edit', [
            'student' => $studentCombined,
            'genders' => Gender::options(),
            'programs' => Program::options(),
            'terms' => Terms::options(),
            'statuses' => Status::options(),
            'immigrations' => Immigration::options(),
            'countries' => Countries::options(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStudentRequest  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $sid = $student->id;
        $pid = $student->pid;

        $input = $request->validated();
        

        if($input['program'] != 'PhD') {
            $input['phd_post'] = null;
        }

        if($input['phd_post'] && !$student->phd_post) {
            $input['phd_post_checked'] = Carbon::now()->format('Y-m-d');
        }

        if(!$input['phd_post'] && $student->phd_post) {
            $input['phd_post_checked'] = null;
        }

        // $person = People::find($pid);
        // $person->last_name = $input['last_name'];
        // $person->first_name = $input['first_name'];
        // $person->email = $input['email'];
        // $person->uid = $input['uid'];
        // $person->ccid = $input['ccid'];
        // $person->gender = $input['gender'];
        // $person->citizenship = $input['citizenship'];
        // $person->immigration = $input['immigration'];
        // $person->amii_start = $input['amii_start'];
        // $person->save();

        $student = Student::find($sid);
        $student->fill($input);
        // $student->program = $input['program'];
        // $student->program_start = $input['program_start'];
        // $student->dept = $input['dept'];
        // $student->active = $input['active'];
        // $student->notes = $input['notes'];
        $student->save();

        $student->person->touch();

        Toast::title('Updated Successfully!')
            ->message('Successfully updated student')
            ->autoDismiss(5);

        return redirect()->route('people.show', [
            'person' => People::find($pid)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
