<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Projects;

class ProjectsTable extends DataTableComponent
{
    protected $model = Projects::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Code", "code")
                ->sortable(),
            Column::make("Total award", "total_award")
                ->sortable(),
            Column::make("Funds before", "funds_before")
                ->sortable(),
            Column::make("Funds after", "funds_after")
                ->sortable(),
            Column::make("Future funding", "future_funding")
                ->sortable(),
            Column::make("Ff start", "ff_start")
                ->sortable(),
            Column::make("Ff end", "ff_end")
                ->sortable(),
            Column::make("Ff verified", "ff_verified")
                ->sortable(),
            Column::make("Project status", "project_status")
                ->sortable(),
            Column::make("Percent spent", "percent_spent")
                ->sortable(),
            Column::make("Oe status", "oe_status")
                ->sortable(),
            Column::make("Auth oe amount", "auth_oe_amount")
                ->sortable(),
            Column::make("Oe auth end", "oe_auth_end")
                ->sortable(),
            Column::make("Oe req status", "oe_req_status")
                ->sortable(),
            Column::make("Financial report", "financial_report")
                ->sortable(),
            Column::make("Supervisor review", "supervisor_review")
                ->sortable(),
            Column::make("Who assigned", "who_assigned")
                ->sortable(),
            Column::make("Priority id", "priority_id")
                ->sortable(),
            Column::make("Eval status id", "eval_status_id")
                ->sortable(),
            Column::make("Notes", "notes")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
