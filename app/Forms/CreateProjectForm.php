<?php

namespace App\Forms;

use App\Models\Fellows;
use App\Models\Projects;
use App\Models\Speedcodes;
use App\Models\FinanceTeam;
use App\Models\ProjectEvaluation;
use App\Models\ProjectPriority;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Checkbox;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class CreateProjectForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('projects.store'))
            ->method('POST');
    }

    public function fields(): array
    {
        return [
            Select::make('code')->label('Speedcode')->options(Speedcodes::options())->required(),
            Select::make('fellow')->label('Fellow')->options(Fellows::optionsAll())->required(),
            Text::make('description')->label('Title'),
            Date::make('award_start')->label('Award Start'),
            Date::make('award_end')->label('Award End'),
            Date::make('fy_start')->label('Fiscal Year Start')->date(['altInput' => true, 'altFormat' => 'm-d'])->required(),
            Date::make('fy_end')->label('Fiscal Year End')->date(['altInput' => true, 'altFormat' => 'm-d'])->required(),
            Checkbox::make('fy_override')->label('Override Award date with Fiscal dates on Fellows User page'),
            Text::make('project')->label('Project ID'),
            Text::make('combo_code')->label('Combo Code'),
            Text::make('total_award')->label('Total Award')->class('money'),
            Text::make('funds_before')->label('Funds available before commitments')->class('money'),
            Text::make('funds_after')->label('Funds available after commitments')->class('money'),
            Text::make('project_status')->label('Project Status')->required(),
            Text::make('percent_spent')->label('Percent Spent')->class('percent'),
            Text::make('oe_status')->label('Over Expenditure Status'),
            Text::make('auth_oe_amount')->label('Authorized O/E Amount')->class('money'),
            Date::make('oe_auth_end')->label('O/E Authorization End Date'),
            Text::make('oe_req_status')->label('O/E Request Status'),
            Text::make('future_funding')->label('Future Funding')->class('money'),
            Date::make('ff_start')->label('FF Start Date'),
            Date::make('ff_end')->label('FF End Date'),
            Text::make('opening_balance')->label('Opening Balance'),
            Checkbox::make('ff_verified')->label('FF Verified'),
            Select::make('who_assigned')->label('Who Assigned?')->options(FinanceTeam::options()),
            Select::make('priority_id')->label('Priority')->options(ProjectPriority::options()),
            Select::make('eval_status_id')->label('Evaluation Status')->options(ProjectEvaluation::options()),
            Checkbox::make('financial_report')->label('Financial Report'),
            Checkbox::make('supervisor_review')->label('Supervisor Review'),
            Select::make('status')->label('Status')->options(Speedcodes::statusOptions()),
            Text::make('program')->label('Program'),
            Textarea::make('notes')->label('Notes'),
            Select::make('balance_alert')->label('Project Balance Alerts')->options(Projects::balanceAlertOptions()),
        ];
    }
}
