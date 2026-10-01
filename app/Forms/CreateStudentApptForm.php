<?php

namespace App\Forms;

use App\Models\Terms;
use App\Models\Gender;
use App\Models\Status;
use App\Models\Fellows;
use App\Models\Program;
use App\Models\ApptType;
use App\Models\Countries;
use App\Models\Immigration;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Hidden;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class CreateStudentApptForm extends AbstractForm
{
    private $student;

    public function __construct($student)
    {
        $this->student = $student;

    }

    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('students.appointments.create'))
            ->method('POST')
            ->fill(['sid' => $this->student->id]);
    }

    public function fields(): array
    {
        $termOptions = Terms::options();
        $apptTypeOptions = ApptType::options();

        return [
            Input::make('sid')->label('Student ID')->required(),
            Select::make('term')->label('Term')->options($termOptions)->required(),
            Date::make('start')->label('Start')->required(),
            Date::make('end')->label('End')->required(),
            Select::make('appt_type')->label('Appointment Type')->options($apptTypeOptions)->required(),
            Input::make('level')->label('Level')->required(),
            Input::make('amount')->label('Amount')->required(),
            Input::make('eform')->label('E-form'),
            Submit::make()
                ->label(__('Add')),
        ];
    }
}
