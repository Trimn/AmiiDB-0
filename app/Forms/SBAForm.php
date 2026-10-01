<?php

namespace App\Forms;

use App\Models\People;
use App\Models\Speedcodes;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class SBAForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('sba.store'))
            ->method('POST')
            ->class('space-y-4');
    }

    public function fields(): array
    {
        return [
            Date::make('date')->label('Date')->required(),
            Select::make('pid')->label('Person')->options(People::listNonFellows())->required(),
            Input::make('reason_code')->label('Reason Code')->required(),
            Textarea::make('reason')->label('Reason')->required(),
            Select::make('debit_speedcode')->label('Debit Speedcode')->options(Speedcodes::optionsAll())->required(),
            Select::make('credit_speedcode')->label('Credit Speedcode')->options(Speedcodes::optionsAll())->required(),
            Input::make('amount')->label('Amount')->required(),
            Input::make('budget_holder')->label('Budget Holder')->required(),
            Textarea::make('notes')->label('Notes'),
        ];
    }
}
