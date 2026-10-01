<?php

namespace App\Forms;

use App\Models\Fellows;
use App\Models\Speedcodes;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class CFSForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('cfs.store'))
            ->method('POST')
            ->class('space-y-4');
    }

    public function fields(): array
    {
        return [
            Text::make('name')->label('Name')->required(),
            Select::make('fid')->label('Fellow')->options(Fellows::options())->required(),
            Select::make('speedcode')->label('Speedcode')->options(Speedcodes::options())->required(),
            Text::make('po')->label('PO #')->required(),
            Text::make('amount')->label('PO Total (CAD $)')->required(),
            Date::make('start')->label('Contract Start')->required(),
            Date::make('end')->label('Contract End')->required(),
            Text::make('remaining')->label('Remaining (CAD $)')->required(),
            Select::make('status')->label('Status')->options(['active' => 'Active', 'inactive' => 'Inactive'])->required(),
            Textarea::make('desc')->label('Description'),
        ];
    }
}
