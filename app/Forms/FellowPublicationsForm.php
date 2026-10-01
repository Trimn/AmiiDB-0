<?php

namespace App\Forms;

use App\Models\Fellows;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class FellowPublicationsForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('publications.store'))
            ->method('POST');
    }

    public function fields(): array
    {
        return [
            Select::make('fid')->label('Fellow')->options(Fellows::options())->rules('required')->required(),
            Input::make('authors')->label('Additional Authors')->rules('nullable|string'),
            Input::make('title')->label('Title')->rules('nullable|string'),
            Input::make('pub_name')->label('Publication Name')->rules('nullable|string'),
            Date::make('pub_date')->label('Publication Date')->rules('nullable|date'),
            Input::make('conf_name')->label('Conference Name')->rules('nullable|string'),
            Input::make('url')->label('URL')->rules('nullable|url'),
            Textarea::make('notes')->label('Notes')->rules('nullable|string'),
        ];
    }
}
