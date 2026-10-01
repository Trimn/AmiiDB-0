<?php

namespace App\Forms;

use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Submit;

class TermsForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('reference.store', 'terms'));
    }

    public function fields(): array
    {
        return [
            Input::make('identifier')->label('Identifier')->rules('size:3')->required(),
            Input::make('semester')->label('Semester')->required(),
            Input::make('year')->label('Year')->rules('integer|digits:4')->required(),
            Input::make('number')->label('Term number')->rules('digits:3'),
            Date::make('start')->label('Start')->required(),
            Date::make('end')->label('End')->required(),
        ];
    }
}
