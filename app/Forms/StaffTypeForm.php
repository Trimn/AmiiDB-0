<?php

namespace App\Forms;

use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class StaffTypeForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('reference.store', 'staff_types'));
    }

    public function fields(): array
    {
        return [
            Input::make('name')->label('Name')->required(),
            Textarea::make('description')->label('Description'),
        ];;
    }
}
