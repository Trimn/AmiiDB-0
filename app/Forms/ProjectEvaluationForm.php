<?php

namespace App\Forms;

use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Submit;

class ProjectEvaluationForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('reference.store', 'project_evaluation'));
    }

    public function fields(): array
    {
        return [
            Input::make('status')->label('Status')->required(),
            Input::make('text_colour')->label('Text Colour')->required(),
            Input::make('bg_colour')->label('BG Colour')->required(),
        ];
    }
}
