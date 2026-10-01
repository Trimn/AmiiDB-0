<?php

namespace App\Forms;

use App\Models\Fellows;
use App\Models\Speedcodes;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Checkbox;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class SpeedcodesForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('speedcodes.store'));
    }

    public function fields(): array
    {
        return [
            Input::make('code')->label('Code')->rules('size:5')->required(),
            Textarea::make('description')->label('Description')->rules('nullable'),
            Input::make('project')->label('Project')->rules('size:12|nullable'),
            Input::make('combo_code')->label('Combo Code')->rules('numeric|digits:9|nullable'),
            Select::make('fellow')->label('Fellow')->options(Fellows::optionsAll())->required(),
            Date::make('award_start')->label('Award Start'),
            Date::make('award_end')->label('Award End'),
            Select::make('status')->label('Status')->options(Speedcodes::statusOptions())->required(),
            Checkbox::make('is_cs')->label('CS Speedcode'),
            Textarea::make('notes')->label('Notes')->rules('nullable'),
        ];
    }
}
