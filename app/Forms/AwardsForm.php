<?php

namespace App\Forms;

use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Submit;

class AwardsForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->method('POST');
    }

    public function fields(): array
    {
        return [
            Text::make('name')->label('Name'),
            Text::make('amount')->label('Amount')->required(),
            Date::make('start')->label('Start'),
            Date::make('end')->label('End'),
            Date::make('award_notification')->label('Award Notification Date'),
        ];
    }
}
