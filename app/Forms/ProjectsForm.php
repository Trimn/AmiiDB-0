<?php

namespace App\Forms;

use App\Models\Projects;
use App\Models\FinanceTeam;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Checkbox;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class ProjectsForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        //
    }

    public function fields(): array
    {
        return [
            Text::make('code')->label('Code')->disabled(),
            Text::make('future_funding')->label('Future Funding'),
            Select::make('who_assigned')->label('Who Assigned?')->options(FinanceTeam::options()),
            Select::make('priority')->label('Priority')->options(Projects::priorities()),
            Checkbox::make('financial_report')->label('Financial Report'),
            Checkbox::make('supervisor_review')->label('Supervisor Review'),
            Textarea::make('notes')->label('Notes'),
        ];
    }
}
