<?php

namespace App\Forms;

use App\Models\Budget;
use App\Models\People;
use App\Models\Speedcodes;
use ProtoneMedia\Splade\SpladeForm;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class BudgetsForm extends AbstractForm
{
    /**
     * @param bool $fromPerson  True when the budget is being created from a person's page.
     *                          The person is known from the route, so the person/description
     *                          and budget_type fields are omitted from both the rendered form
     *                          and the validation rules.
     */
    public function __construct(protected bool $fromPerson = false)
    {
    }

    public function configure(SpladeForm $form)
    {
        $form
            ->method('POST');
    }

    public function fields(): array
    {
        $isFromPersonPage = $this->fromPerson;
        $showBudgetType = !$isFromPersonPage;

        $fields = [];

        // Only hide person/description fields when creating from person page (since person is already known)
        // When editing from budgets page, always show these fields so user can see/change the person
        if (!$isFromPersonPage) {
            $fields[] = Select::make('pid')->label('Person')->options(['' => 'Select a person...'] + People::listAll())->rules('nullable|integer|required_without:description')->attributes(['disabled' => !Auth::user()->can('edit')]);
            $fields[] = Text::make('description')->label('Description (if not related to a person)')->rules('nullable|string|max:255|required_without:pid')->attributes(['readonly' => !Auth::user()->can('edit')]);
        }
        
        $fields = array_merge($fields, [
            Date::make('start')->label('Start Date')->rules('required|date')->required()->attributes(['readonly' => !Auth::user()->can('edit')]),
            Date::make('end')->label('End Date')->rules('required|date')->required()->attributes(['readonly' => !Auth::user()->can('edit')]),
            Text::make('amount')->label('Budgeted Amount')->rules('required|numeric')->required()->class('money')->attributes(['readonly' => !Auth::user()->can('edit')]),
            Select::make('code')->label('Speedcode')->options(Speedcodes::options())->rules('required|string')->required()->attributes(['disabled' => !Auth::user()->can('edit')]),
            Select::make('type')->label('Type')->options(Budget::typeOptions())->rules('required|string')->required()->attributes(['disabled' => !Auth::user()->can('edit')]),
        ]);

        if ($showBudgetType) {
            $fields[] = Select::make('budget_type')
                ->label('Budget Type')
                ->options(Budget::budgetTypeOptions())
                ->rules('required|string')
                ->required()
                ->choices(['allowHTML' => true])
                ->attributes(['disabled' => !Auth::user()->can('edit')]);
        }

        $fields[] = Textarea::make('notes')
            ->label('Notes')
            ->rules('nullable|string')
            ->attributes(['readonly' => !Auth::user()->can('edit')]);

        return $fields;
    }
}


