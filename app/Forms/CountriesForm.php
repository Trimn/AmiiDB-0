<?php

namespace App\Forms;

use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Submit;

class CountriesForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('reference.store', 'countries'));
    }

    public function fields(): array
    {
        return [
            Input::make('country')->label('Country')->required(),
        ];
    }
}
