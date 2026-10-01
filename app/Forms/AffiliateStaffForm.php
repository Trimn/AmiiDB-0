<?php

namespace App\Forms;

use App\Models\Affiliates;
use App\Models\AffiliateStaff;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class AffiliateStaffForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('affiliate_staff.store'))
            ->method('POST')
            ->class('space-y-4');
    }

    public function fields(): array
    {
        return [
            Text::make('first_name')->label(__('First Name'))->required()->rules('required'),
            Text::make('last_name')->label('Last Name')->required()->rules('required'),
            Select::make('sup_id')->label('Supervisor')->options(AffiliateStaff::supervisors())->choices()->required()->rules('required'),
            Text::make('supervisor2')->label('Supervisor 2')->rules('nullable'),
            Select::make('type')->label('Type')->options(AffiliateStaff::types())->required()->rules('required'),
            Date::make('start')->label('Start Date')->rules('nullable'),
            Date::make('end')->label('End Date')->rules('nullable'),
            Textarea::make('notes')->label('Notes')->rules('nullable'),
        ];
    }
}
