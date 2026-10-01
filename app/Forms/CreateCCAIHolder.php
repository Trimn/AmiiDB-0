<?php

namespace App\Forms;

use App\Models\Gender;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class CreateCCAIHolder extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->method('POST');
    }

    public function fields(): array
    {
        return [
            Text::make('last_name')->label(__('Last Name'))->required(),
            Text::make('first_name')->label(__('First Name'))->required(),
            Text::make('uid')->label(__('University ID'))->required(),
            Text::make('ccid')->label(__('CCID'))->required(),
            Text::make('title')->label('Title'),
            Text::make('dept')->label('Dept'),
            Text::make('email')->label('Alternate Email'),
            Select::make('gender')->options(Gender::options())->label('Gender'),
            Text::make('alias')->label(__('Alias')),
            Textarea::make('notes')->label(__('Notes')),
        ];
    }
}
