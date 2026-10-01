<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AffiliateStaff;
use App\Forms\AffiliateStaffForm;
use App\Tables\AffiliateStaffTable;
use ProtoneMedia\Splade\FormBuilder\Submit;

class AffiliateStaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('affiliate_staff.index', [
            'table' => AffiliateStaffTable::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $form = AffiliateStaffForm::make()
            ->action(route('affiliate_staff.store'))
            ->fields([
                Submit::make()->label('Add'),
            ]);
        return view('affiliate_staff.create', [
            'form' => $form,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->validate(AffiliateStaffForm::rules());

        $staff = new AffiliateStaff($input);
        $staff->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(AffiliateStaff $affiliateStaff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AffiliateStaff $affiliateStaff)
    {
        $form = AffiliateStaffForm::make()
            ->action(route('affiliate_staff.update', $affiliateStaff))
            ->fields([
                Submit::make()->label('Update'),
            ])
            ->fill($affiliateStaff);
        return view('affiliate_staff.edit', [
            'form' => $form,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AffiliateStaff $affiliateStaff)
    {
        $input = $request->validate(AffiliateStaffForm::rules());

        $affiliateStaff->fill($input);
        $affiliateStaff->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AffiliateStaff $affiliateStaff)
    {
        //
    }
}
