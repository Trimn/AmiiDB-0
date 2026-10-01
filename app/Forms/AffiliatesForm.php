<?php

namespace App\Forms;

use App\Models\Gender;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class AffiliatesForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->method('POST');
    }

    public function fields(): array
    {
        return [
            Text::make('last_name')->label('Last Name')->required()->rules('required|string'),
            Text::make('first_name')->label('First Name')->required()->rules('required|string'),
            // Select::make('gender')->options(Gender::options())->label('Gender')->rules('nullable'),
            Text::make('uid')->label('ID')->required()->rules('required|numeric'),
            Text::make('title')->label('Title')->rules('nullable|string'),
            Text::make('affiliation')->label('Affiliation')->rules('nullable|string'),
            Text::make('email')->label('Email')->rules('nullable|email'),
            Text::make('alternate_email')->label('Alternate Email')->rules('nullable|email'),
            Text::make('website')->label('Website')->rules('nullable|string'),
            Textarea::make('notes')->label('Notes')->rules('nullable'),
        ];
    }
}
