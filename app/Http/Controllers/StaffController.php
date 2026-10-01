<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Gender;
use App\Models\People;
use App\Models\Status;
use App\Models\Countries;
use App\Models\StaffType;
use App\Tables\StaffTable;
use App\Models\Immigration;
use App\Models\Supervisors;
use App\Models\StaffSubtype;
use App\Forms\CreateStaffForm;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\Facades\Toast;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Http\Requests\CreateStaffFormRequest;
use App\Http\Requests\CreateStaffRecordRequest;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staff = QueryBuilder::for(Staff::class)
            ->allowedIncludes(['person', 'position', 'pos_subtype', 'person.supervisor.person'])
            ->get();
        // dd($staff, $staff[0]->person->supervisor);
        return view('staff.index', [
            'staff' => StaffTable::class
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('staff.create', [
            'form' => CreateStaffForm::class
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStaffRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStaffFormRequest $request)
    {
        $input = $request->validated();

        $vals = [
            'last_name' => $input['last_name'],
            'first_name' => $input['first_name'],
            'email' => $input['email'],
            'uid' => $input['uid'],
            'ccid' => $input['ccid'],
            'gender' => $input['gender'],
            'citizenship' => $input['citizenship'],
            'immigration' => $input['immigration'],
            'amii_start' => $input['amii_start'],
            'wp_start' => $input['wp_start'],
            'wp_end' => $input['wp_end'],
            'supervisor' => $input['supervisor'],
            'supervisor2' => $input['supervisor2'],
        ];

        $person = People::where('uid', '=', $input['uid'])->first();
        if($person) {
            $person->fill($vals);
            $person->save();
        }
        else {
            $person = People::create($vals);
        }

        $staff = Staff::updateOrCreate(
            [
                'pid' => $person->id,
                'pos_type' => $input['pos_type'],
                'subtype' => $input['subtype'],
                'active' => $input['active'],
                'notes' => $input['notes'],
                'job_title' => $input['job_title'],
                'dept' => $input['dept'],
                'pdf_completed' => $input['pdf_completed'] ?? null,
            ]
        );

        Toast::title('Created Successfully!')
            ->message('Successfully created staff member')
            ->autoDismiss(5);

        return redirect()->route('staff');
    }

    public function createRecord(People $person) {
        return view('staff.createrec', [
            'person' => $person
        ]);
    }

    public function storeRecord(CreateStaffRecordRequest $request, People $person) {
        $input = $request->validated();
        Staff::updateOrCreate([
            'pid' => $person->id,
            'pos_type' => $input['pos_type'],
            'subtype' => $input['subtype'],
            'active' => $input['active'],
            'notes' => $input['notes'],
            'job_title' => $input['job_title'],
            'dept' => $input['dept'],
            'pdf_completed' => $input['pdf_completed'] ?? null,
        ]);

        $person->touch();

        return redirect()->route('people.show', [
            'person' => $person
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff)
    {
        $staff->load('pos_subtype');
        $staffCombined = QueryBuilder::for(Staff::class)
            ->join('people', 'staff.pid', '=', 'people.id')
            ->select('staff.*', 'people.last_name as last_name', 'people.first_name as first_name', 'people.email as email', 'people.uid as uid', 'people.ccid as ccid', 'people.gender as gender', 'people.citizenship as citizenship', 'people.immigration as immigration', 'people.amii_start as amii_start')
            ->where('staff.id', '=', $staff->id)
            ->get()
            ->first();
        $staffCombined->pos_subtype = $staff->pos_subtype;

        return view('staff.edit', [
            'staff' => $staffCombined,
            'genders' => Gender::options(),
            'statuses' => Status::options(),
            'immigrations' => Immigration::options(),
            'countries' => Countries::options(),
            'posTypes' => StaffType::options(),
            'subtypes' => StaffSubtype::options(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStaffRequest  $request
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        $sid = $staff->id;
        $pid = $staff->pid;

        $input = $request->validated();

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

        $staff = Staff::find($sid);
        $staff->fill($input);
        $staff->save();
        $staff->person->fill($input);
        $staff->person->save();
        $staff->person->touch();

        Toast::title('Updated Successfully!')
            ->message('Successfully updated staff member')
            ->autoDismiss(5);

        return redirect()->route('people.show', [
            'person' => People::find($pid)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff)
    {
        //
    }
}
