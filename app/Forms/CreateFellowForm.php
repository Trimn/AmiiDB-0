<?php

namespace App\Forms;

use App\Models\Terms;
use App\Models\Gender;
use App\Models\Status;
use App\Models\Fellows;
use App\Models\Program;
use App\Models\Countries;
use App\Models\Immigration;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\File;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Textarea;
use ProtoneMedia\Splade\FormBuilder\Checkbox;

class CreateFellowForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('fellows.create'))
            ->method('POST');
    }

    public function fields(): array
    {
        $genderOptions = Gender::options();
        $immigrationOptions = Immigration::options();
        $countryOptions = Countries::options();

        return [
            Input::make('first_name')->label('First Name')->required(),
            Input::make('last_name')->label('Last Name')->required(),
            Select::make('gender')->options(Gender::options())->label('Gender'),
            Input::make('ccid')->label('CCID')->required(),
            Input::make('title')->label('Title'),
            Input::make('dept')->label('Dept'),
            File::make('photo')->label('Upload photo')->accept(['image/png', 'image/jpeg', 'image/webp'])->filepond()->server()->preview(),
            Input::make('report_id')->label('Reports to ID'),
            Input::make('uid')
                ->label('Supervisor ID')
                ->required(),
            Checkbox::make('temporary_id')
                ->label('Temporary ID (Will generate a random ID)'),
            Input::make('email')->label('Alternate Email'),
            // Input::make('assistant_name')->label('Assistant Name'),
            // Input::make('assistant_email')->label('Assistant Email'),
            Date::make('fellow_start')->label('Fellow Start Date'),
            Input::make('website')->label('Website'),
            Input::make('committees')->label('Committees'),
            Input::make('office')->label('Office #'),
            Input::make('phone')->label('Phone #'),
            Input::make('alias')->label('RHP Export name'),
            Input::make('pub_platform_primary')->label('Main Publication Platform'),
            Input::make('pub_list_location')->label('Publication List Location'),
            Checkbox::make('ccai_chair')->label('CCAI Chair'),
            Date::make('ccai_start')->label('CCAI Start Date'),
            Date::make('ccai_end')->label('CCAI End Date'),
            Textarea::make('notes')->label('Notes'),

            Submit::make()
                ->label(__('Add')),
        ];
    }
}
