<?php

namespace App\Forms;

use App\Models\Gender;
use App\Models\Fellows;
use App\Models\Speedcodes;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Checkbox;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class VisitorForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->method('POST');
    }

    public function fields(): array
    {
        return [
            Text::make('uid')->label('University ID')->required()->rules('numeric|nullable'),
            Text::make('first_name')->label('First Name')->required()->rules('string'),
            Text::make('last_name')->label('Last Name')->required()->rules('string'),
            Select::make('gender')->label('Gender')->options(Gender::options())->rules('nullable'),
            Checkbox::make('ccid_requested')->label('CCID Requested?')->rules('nullable'),
            Date::make('ccid_req_date')->label('CCID Request Date')->rules('nullable|date'),
            Text::make('ccid')->label('CCID')->rules('nullable'),
            Text::make('email')->label('Alternate Email')->rules('email|nullable'),
            Date::make('dob')->label('Date of Birth'),
            Select::make('status')->label('Status')->options(['Active', 'Inactive', 'Coming'])->required()->rules('required'),
            Select::make('supervisor')->label('Inviting Fellow')->options(Fellows::options())->required()->rules('required'),
            Select::make('speedcode')->label('Speedcode')->options(Speedcodes::options())->required()->rules('required'),
            Text::make('fvca_category')->label('FVCA Category')->rules('nullable'),
            Text::make('fvca_url')->label('FVCA Link')->rules('url|nullable'),
            Checkbox::make('letter_of_invitation')->label('Letter of Invitation')->rules('nullable'),
            Text::make('airfare')->label('Airfare')->rules('nullable'),
            Text::make('accomodation')->label('Accomodation')->rules('nullable'),
            Date::make('arrival')->label('Arrival Date')->rules('nullable'),
            Date::make('departure')->label('Departure Date')->rules('nullable'),
            Date::make('on_campus')->label('First day on campus')->rules('nullable'),
            Textarea::make('workspace')->label('Workspace')->rules('nullable'),
            Text::make('uofa_funding')->label('Funding from UofA')->rules('nullable'),
            Text::make('payment_amount')->label('Payment amount')->rules('nullable'),
            Text::make('payment_category')->label('Payment amount category')->rules('nullable'),
            Checkbox::make('welcomed')->label('Welcome Completed')->rules('nullable'),
            Text::make('welcome_url')->label('Welcome Link')->rules('url|nullable'),
            Checkbox::make('paf_completed')->label('PAF completed')->rules('nullable'),
            Text::make('paf_url')->label('PAF Link')->rules('url|nullable'),
            Textarea::make('notes')->label('Notes')->rules('nullable'),
        ];
    }
}
