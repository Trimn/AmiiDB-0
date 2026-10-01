<?php

namespace App\Forms;

use App\Models\Fellows;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class ContactsForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('contacts.store'))
            ->method('POST');
    }

    public function fields(): array
    {
        return [
            Text::make('name')->label('Name')->required()->rules('required'),
            Text::make('position')->label('Position')->rules('nullable'),
            Text::make('email')->label('Email')->rules('nullable'),
            Text::make('phone')->label('Phone #')->rules('nullable'),
            Text::make('department')->label('Department')->rules('nullable'),
            Select::make('fellow_id')->label('Fellow')->options(Fellows::optionsAll())->rules('nullable'),
            Textarea::make('notes')->label('Notes')->rules('nullable'),
        ];
    }
}
