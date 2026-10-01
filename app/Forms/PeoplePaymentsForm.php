<?php

namespace App\Forms;

use App\Models\People;
use App\Models\Speedcodes;
use ProtoneMedia\Splade\SpladeForm;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class PeoplePaymentsForm extends AbstractForm
{   
    public function configure(SpladeForm $form)
    {
        $form
            ->method('POST');
    }

    public function fields(): array
    {
        return [
            Input::make('name')->label('Payment Name')->rules('required')->required()->attributes(['readonly' => !Auth::user()->can('edit')]),
            Date::make('start')->label('Start Date')->rules('required|date')->required()->attributes(['readonly' => !Auth::user()->can('edit')]),
            Date::make('end')->label('End Date')->rules('required|date')->required()->attributes(['readonly' => !Auth::user()->can('edit')]),
            Input::make('amount')->label('Payment Amount')->rules('required|numeric')->required()->attributes(['readonly' => !Auth::user()->can('edit')]),
            Select::make('code')->label('Speedcode')->options(Speedcodes::options())->rules('required|string')->required()->attributes(['disabled' => !Auth::user()->can('edit')]),
            Textarea::make('notes')->label('Notes')->rules('nullable')->required(false)->attributes(['readonly' => !Auth::user()->can('edit')]),
        ];
    }
}
