<?php

namespace App\Forms;

use App\Models\Gender;
use App\Models\Fellows;
use App\Models\Countries;
use App\Models\Speedcodes;
use App\Models\Immigration;
use ProtoneMedia\Splade\SpladeForm;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class PaymentsForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->method('POST');
    }

    public function fields(): array
    {
        return [
            Input::make('uid')->label('University ID')->required()->rules('required|numeric')->attributes(['readonly' => !Auth::user()->can('edit')]),
            Input::make('last_name')->label('Last Name')->required()->rules('required')->attributes(['readonly' => !Auth::user()->can('edit')]),
            Input::make('first_name')->label('First Name')->required()->rules('required')->attributes(['readonly' => !Auth::user()->can('edit')]),
            Input::make('ccid')->label('CCID')->required()->rules('required')->attributes(['readonly' => !Auth::user()->can('edit')]),
            Select::make('supervisor')->label('Supervisor')->rules('nullable')->options(Fellows::optionsAll())->attributes(['disabled' => !Auth::user()->can('edit')]),
            Input::make('supervisor2')->label('Second Supervisor')->rules('nullable')->attributes(['readonly' => !Auth::user()->can('edit')]),
            Input::make('name')->label('Payment Name')->rules('required')->required()->attributes(['readonly' => !Auth::user()->can('edit')]),
            Date::make('start')->label('Start Date')->rules('required|date')->required()->attributes(['readonly' => !Auth::user()->can('edit')]),
            Date::make('end')->label('End Date')->rules('required|date')->required()->attributes(['readonly' => !Auth::user()->can('edit')]),
            Input::make('amount')->label('Payment Amount')->rules('required|numeric')->required()->attributes(['readonly' => !Auth::user()->can('edit')]),
            Select::make('code')->label('Speedcode')->options(Speedcodes::options())->rules('required|string')->required()->attributes(['disabled' => !Auth::user()->can('edit')]),
            Textarea::make('notes')->label('Notes')->rules('nullable')->required(false)->attributes(['readonly' => !Auth::user()->can('edit')]),
        ];
    }
}
