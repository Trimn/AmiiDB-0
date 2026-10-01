<?php

namespace App\Forms;

use App\Models\Rates;
use App\Models\Terms;
use App\Models\Program;
use App\Models\Immigration;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Checkbox;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class RatesForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('reference.store', 'rates'));
    }

    public function fields(): array
    {
        return [
            Select::make('term')->label('Term')->options(Terms::options())->rules('required')->required(),
            Select::make('program')->label('Program')->options(Program::options())->rules('required')->required(),
            Input::make('program_year')->label('Program Year')->rules('required|integer|gte:0|lt:100')->required(),
            Input::make('salary_step')->label('Salary Step')->rules('required|integer|gte:0|lt:100')->required(),
            Select::make('rate_type')->label('Rate Type')->options(Rates::options())->rules('required')->required(),
            Select::make('immigration')->label('Immigration')->options(Immigration::options())->rules('required')->required(),
            Input::make('cs_award')->label('CS Award $')->rules('required|numeric|gte:0')->required(),
            Input::make('cs_salary')->label('CS Salary $')->rules('required|numeric|gte:0')->required(),
            Input::make('amii_topup')->label('Amii Topup $')->rules('required|numeric|gte:0')->required(),
            Input::make('int_idf')->label('International IDF $')->rules('required|numeric|gte:0')->required(),
            Input::make('int_amii')->label('International Amii $')->rules('required|numeric|gte:0')->required(),
            Textarea::make('notes')->label('Notes')->rules('nullable')->required(false),
        ];
    }
}
