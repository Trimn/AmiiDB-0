<?php

namespace App\Forms;

use App\Models\People;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class ContactPreferencesForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->method('POST');
    }

    public function fields(): array
    {
        return [
            Select::make('pid')->options(People::listAll())->label('Person')->required(),
            Text::make('alternate_email')->label('Alternate Email')->rules('nullable|email'),
            Select::make('contact_method')->options(['Slack', 'UofA Email', 'Alternate Email'])->label('Preferred Contact Method')->rules('nullable'),
            Text::make('meeting_email')->label('Meeting Email')->rules('nullable'),
            Text::make('calendar_url')->label('Additional Calendar URL')->rules('nullable|url'),
            Textarea::make('work_schedule')->label('Work Schedule')->rules('nullable'),
            Text::make('doc_pref')->label('Document Signing Preference')->rules('nullable'),
            Text::make('signature_url')->label('Digital Signature URL')->rules('nullable|url'),
            Text::make('assistant_name')->label('Assistant Name')->rules('nullable'),
            Text::make('assistant_email')->label('Assistant Email')->rules('nullable|email'),
            Textarea::make('notes')->label('Notes')->rules('nullable'),
        ];
    }
}
