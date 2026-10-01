<?php

namespace App\Forms;

use App\Models\Terms;
use App\Models\Gender;
use App\Models\Status;
use App\Models\Fellows;
use App\Models\Program;
use App\Models\Countries;
use App\Models\Immigration;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class CreateStudentForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('students.create'))
            ->method('POST');
    }

    public function fields(): array
    {
        $genderOptions = Gender::options();
        $immigrationOptions = Immigration::options();
        $countryOptions = Countries::options();
        $termOptions = Terms::options();
        $programOptions = Program::options();
        $statusOptions = Status::options();
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
            Select::make('supervisor')->label('Primary Supervisor')->options($fellowOptions),
            Input::make('supervisor2')->label('Secondary Supervisor'),
            Date::make('amii_start')->label('Amii Start Date'),
            Date::make('amii_end')->label('Amii End Date'),
            Select::make('program')->label('Program')->options($programOptions)->required(),
            //Select::make('program_start')->label('Program Start')->options($termOptions)->required(),
            Input::make('program_start')->label('Program Start')->required(),
            Input::make('dept')->label('Dept')->required(),
            Date::make('convocation')->label('Final Exam Pass Date'),
            Select::make('active')->label('Status')->options($statusOptions)->required(),
            Textarea::make('notes')->label('Notes'),

            Submit::make()
                ->label(__('Add')),
        ];
    }
}
