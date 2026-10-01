<?php

namespace App\Forms;

use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Submit;

class GenderForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('reference.store', 'gender'));
    }

    public function fields(): array
    {
        return [
            Input::make('gender')->label('Gender')->required(),
        ];
    }
}
