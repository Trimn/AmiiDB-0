<?php

namespace App\Forms;

use App\Models\Gender;
use App\Models\Status;
use App\Models\Fellows;
use App\Models\Countries;
use App\Models\StaffType;
use App\Models\Immigration;
use App\Models\StaffSubtype;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\Components\Defer;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class CreateStaffForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('staff.create'))
            ->method('POST');
    }

    public function fields(): array
    {
        $genderOptions = Gender::options();
        $immigrationOptions = Immigration::options();
        $countryOptions = Countries::options();
        $statusOptions = Status::options();
        $posTypeOptions = StaffType::options();
        $subtypeOptions = StaffSubtype::options();
        $fellowOptions = Fellows::options();

        return [
            Input::make('uid')->label('University ID')->required(),
            Input::make('last_name')->label('Last Name')->required(),
            Input::make('first_name')->label('First Name')->required(),
            Input::make('email')->label('Alternate Email'),
            Input::make('ccid')->label('CCID')->required(),
            Select::make('gender')->label('Gender')->options($genderOptions)->required(),
            Select::make('citizenship')->label('Citizenship')->options($countryOptions)->required(),
            Select::make('immigration')->label('Immigration')->options($immigrationOptions)->required(),
            Date::make('amii_start')->label('Amii Start Date'),
            Select::make('supervisor')->label('Primary Supervisor')->options($fellowOptions)->required(),
            Input::make('supervisor2')->label('Secondary Supervisor'),
            Input::make('job_title')->label('Job Title'),
            Input::make('dept')->label('Department'),
            Select::make('pos_type')->label('Position Type')->options($posTypeOptions)->required(),
            Select::make('subtype')->label('Subtype')->options($subtypeOptions)->required(),
            Date::make('pdf_completed')->label('PDF Completed'),
            Date::make('wp_start')->label('Work Permit Start'),
            Date::make('wp_end')->label('Work Permit End'),
            Select::make('active')->label('Status')->options($statusOptions)->required(),
            Textarea::make('notes')->label('Notes'),

            Submit::make()
                ->label(__('Add')),
        ];
    }
}
