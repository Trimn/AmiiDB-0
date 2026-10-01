<?php

namespace App\Forms;

use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Submit;

class ProjectPriorityForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('reference.store', 'project_priority'));
    }

    public function fields(): array
    {
        return [
            Input::make('priority')->label('Priority')->required(),
            Input::make('text_colour')->label('Text Colour')->required(),
            Input::make('bg_colour')->label('BG Colour')->required(),
        ];
    }
}
