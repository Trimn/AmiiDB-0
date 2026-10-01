<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\People;
use App\Models\Projects;
use App\Forms\BudgetsForm;
use Illuminate\Http\Request;
use App\Tables\BudgetsTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\FormBuilder\Submit;

class BudgetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('view')) {
            Toast::danger('You do not have permission to view budgets.')->autoDismiss(15);
            return redirect()->route('dashboard');
        }

        return view('budgets.index', [
            'table' => BudgetsTable::class,
        ]);
    }

    /**
     * Show the form for creating a new resource from budgets page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('edit')) {
            Toast::danger('You do not have permission to create budgets.')->autoDismiss(15);
            return redirect()->back();
        }

        $form = BudgetsForm::make()
            ->action(route('budgets.store'))
            ->fields([
                Submit::make()->label('Add')
            ]);
        return view('budgets.create', [
            'form' => $form,
        ]);
    }

    /**
     * Show the form for creating a new resource from person page.
     *
     * @return \Illuminate\Http\Response
     */
    public function createFromPerson(People $person)
    {
        if (!Auth::user()->can('edit')) {
            Toast::danger('You do not have permission to create budgets.')->autoDismiss(15);
            return redirect()->back();
        }

        $form = BudgetsForm::make(fromPerson: true)
            ->action(route('budgets.store.person', $person))
            ->fields([
                Submit::make()->label('Add')
            ]);
        return view('budgets.create', [
            'form' => $form,
        ]);
    }

    /**
     * Show the form for creating a new resource from project page.
     *
     * @return \Illuminate\Http\Response
     */
    public function createFromProject(Projects $projects)
    {
        if (!Auth::user()->can('edit')) {
            Toast::danger('You do not have permission to create budgets.')->autoDismiss(15);
            return redirect()->back();
        }

        $form = BudgetsForm::make()
            ->action(route('budgets.store.project', $projects))
            ->fields([
                Submit::make()->label('Add')
            ])
            ->fill(['code' => $projects->code]);
        return view('budgets.create', [
            'form' => $form,
        ]);
    }

    /**
     * Store a newly created resource in storage from budgets page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('edit')) {
            Toast::danger('You do not have permission to create budgets.')->autoDismiss(15);
            return redirect()->back();
        }

        $rules = BudgetsForm::rules();
        // Add validation: either pid or description must be provided
        $rules['pid'] = array_merge($rules['pid'] ?? [], ['nullable', 'integer', 'required_without:description']);
        $rules['description'] = array_merge($rules['description'] ?? [], ['nullable', 'string', 'max:255', 'required_without:pid']);
        
        $input = $request->validate($rules);
        
        // Ensure only one is set
        if (!empty($input['pid'])) {
            $input['description'] = null;
        } else {
            $input['pid'] = null;
        }
        
        // Automatically set budget_type to "Salaries & Benefits" if not provided
        if (!isset($input['budget_type']) || empty($input['budget_type'])) {
            $input['budget_type'] = 'Salaries & Benefits';
        }
        
        $budget = new Budget($input);
        $budget->save();

        Toast::success('Budget created successfully.')->autoDismiss(15);
        return redirect()->route('budgets');
    }

    /**
     * Store a newly created resource in storage from person page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFromPerson(Request $request, People $person)
    {
        if (!Auth::user()->can('edit')) {
            Toast::danger('You do not have permission to create budgets.')->autoDismiss(15);
            return redirect()->back();
        }

        // Use the same field set as createFromPerson() so the rules match what was rendered
        // (pid, description and budget_type are not on the form; pid comes from the route).
        $rules = BudgetsForm::rules(fromPerson: true);
        $input = $request->validate($rules);
        $input['pid'] = $person->id;
        $input['description'] = null; // Clear description when creating from person page
        
        // Automatically set budget_type to "Salaries & Benefits" if not provided (when creating from person page)
        if (!isset($input['budget_type']) || empty($input['budget_type'])) {
            $input['budget_type'] = 'Salaries & Benefits';
        }
        
        $budget = new Budget($input);
        $budget->save();

        Toast::success('Budget created successfully.')->autoDismiss(15);
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage from project page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFromProject(Request $request, Projects $projects)
    {
        if (!Auth::user()->can('edit')) {
            Toast::danger('You do not have permission to create budgets.')->autoDismiss(15);
            return redirect()->back();
        }

        $rules = BudgetsForm::rules();
        // Add validation: either pid or description must be provided
        $rules['pid'] = array_merge($rules['pid'] ?? [], ['nullable', 'integer', 'required_without:description']);
        $rules['description'] = array_merge($rules['description'] ?? [], ['nullable', 'string', 'max:255', 'required_without:pid']);
        
        $input = $request->validate($rules);
        
        // Ensure only one is set
        if (!empty($input['pid'])) {
            $input['description'] = null;
        } else {
            $input['pid'] = null;
        }
        
        // Set the code from the project
        $input['code'] = $projects->code;
        
        // Automatically set budget_type to "Salaries & Benefits" if not provided
        if (!isset($input['budget_type']) || empty($input['budget_type'])) {
            $input['budget_type'] = 'Salaries & Benefits';
        }
        
        $budget = new Budget($input);
        $budget->save();

        Toast::success('Budget created successfully.')->autoDismiss(15);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function show(Budget $budget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function edit(Budget $budget)
    {
        if (!Auth::user()->can('view')) {
            Toast::danger('You do not have permission to view budgets.')->autoDismiss(15);
            return redirect()->back();
        }

        $data = $budget->toArray();
        $data['start'] = $budget->start?->format('Y-m-d');
        $data['end'] = $budget->end?->format('Y-m-d');
        $data['amount'] = is_null($data['amount']) ? $data['amount'] : Number::currency($data['amount']);

        $form = BudgetsForm::make()
            ->action(route('budgets.update', $budget))
            ->fields(Auth::user()->can('edit') ? [
                Submit::make()->label('Update')
            ] : [])
            ->fill($data);
        return view('budgets.edit', [
            'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Budget $budget)
    {
        if (!Auth::user()->can('edit')) {
            Toast::danger('You do not have permission to update budgets.')->autoDismiss(15);
            return redirect()->back();
        }

        // Convert currency formatted amount back to number before validation
        $request->merge([
            'amount' => is_numeric($request->amount) 
                ? $request->amount 
                : floatval(str_replace(['$', ',', ' '], '', $request->amount))
        ]);

        $rules = BudgetsForm::rules();
        // Add validation: either pid or description must be provided, but not both
        $rules['pid'] = array_merge($rules['pid'] ?? [], ['required_without:description']);
        $rules['description'] = array_merge($rules['description'] ?? [], ['required_without:pid', 'nullable', 'string', 'max:255']);
        
        $input = $request->validate($rules);
        
        // Ensure only one is set
        if (!empty($input['pid'])) {
            $input['description'] = null;
        } else {
            $input['pid'] = null;
        }
        
        $budget->update($input);

        Toast::success('Budget updated successfully.')->autoDismiss(15);
        return redirect()->route('budgets');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function destroy(Budget $budget)
    {
        if (!Auth::user()->can('edit')) {
            Toast::danger('You do not have permission to delete budgets.')->autoDismiss(15);
            return redirect()->back();
        }

        $budget->delete();
        Toast::success('Budget deleted successfully.')->autoDismiss(15);
        return redirect()->back();
    }
}

