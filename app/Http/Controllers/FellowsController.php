<?php

namespace App\Http\Controllers;

use App\Models\People;
use App\Models\Fellows;
use App\Models\Student;
use App\Models\Supervisors;
use App\Tables\FellowsTable;
use App\Forms\CreateFellowForm;
use App\Http\Controllers\Controller;
use ProtoneMedia\Splade\Facades\Toast;
use App\Http\Requests\StoreFellowsRequest;
use App\Http\Requests\UpdateFellowsRequest;
use ProtoneMedia\Splade\FileUploads\HandleSpladeFileUploads;

class FellowsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('fellows.index', [
            'fellows' => FellowsTable::class
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fellows.create', [
            'form' => CreateFellowForm::class
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFellowsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFellowsRequest $request)
    {
        HandleSpladeFileUploads::forRequest($request);
        $input = $request->validated();

        $person = People::updateOrCreate(
            [
                'last_name' => $input['last_name'],
                'first_name' => $input['first_name'],
                'gender' => $input['gender'],
                'email' => $input['email'],
                'uid' => $input['uid'],
                'ccid' => $input['ccid'],
            ]
        );

        $path = null;
        if($request->photo) {
            $path = $request->photo->store('images', 'public');
        }

        Fellows::updateOrCreate([
            'pid' => $person->id,
            'report_id' => $input['report_id'],
            'start' => $input['fellow_start'],
            'notes' => $input['notes'],
            'title' => $input['title'],
            'photo' => $path,
            'website' => $input['website'],
            'committees' => $input['committees'],
            'dept' => $input['dept'],
            'office' => $input['office'],
            'phone' => $input['phone'],
            'alias' => $input['alias'],
            'pub_platform_primary' => $input['pub_platform_primary'],
            'pub_list_location' => $input['pub_list_location'],
            'temporary_id' => $input['temporary_id'] ?? false,
            'ccai_chair' => $input['ccai_chair'] ?? false,
            'ccai_start' => $input['ccai_start'],
            'ccai_end' => $input['ccai_end'],
        ]);

        Toast::title('Created Successfully!')
            ->message('Successfully created fellow')
            ->autoDismiss(5);

        return redirect()->route('fellows');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fellows  $fellows
     * @return \Illuminate\Http\Response
     */
    public function show(Fellows $fellows)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fellows  $fellows
     * @return \Illuminate\Http\Response
     */
    public function edit(Fellows $fellows)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFellowsRequest  $request
     * @param  \App\Models\Fellows  $fellows
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFellowsRequest $request, Fellows $fellow)
    {
        HandleSpladeFileUploads::forRequest($request, 'photo');
        $input = $request->validated();
        $path = $fellow->photo;
        if($request->photo) {
            $path = $request->photo->store('images', 'public');
        }
        
        $person = $fellow->person;
        $person->last_name = $input['last_name'];
        $person->first_name = $input['first_name'];
        $person->gender = $input['gender'];
        $person->ccid = $input['ccid'];
        $person->email = $input['email'];
        $person->uid = $input['uid'];
        $person->save();
        $person->touch();

        $fellow->report_id = $input['report_id'];
        $fellow->start = $input['start'];
        $fellow->notes = $input['notes'];
        $fellow->title = $input['title'];
        $fellow->photo = $path;
        $fellow->website = $input['website'];
        $fellow->committees = $input['committees'];
        $fellow->dept = $input['dept'];
        $fellow->office = $input['office'];
        $fellow->phone = $input['phone'];
        $fellow->alias = $input['alias'];
        $fellow->pub_platform_primary = $input['pub_platform_primary'];
        $fellow->pub_list_location = $input['pub_list_location'];
        $fellow->temporary_id = $input['temporary_id'] ?? false;
        $fellow->ccai_chair = $input['ccai_chair'] ?? false;
        $fellow->ccai_start = $input['ccai_start'];
        $fellow->ccai_end = $input['ccai_end'];
        $fellow->save();

        return redirect()->route('people.show', $fellow->pid);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fellows  $fellows
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fellows $fellows)
    {
        //
    }
}
