<?php

namespace App\Http\Controllers;

use App\Models\Rates;
use App\Models\Terms;
use App\Models\Action;
use App\Models\Gender;
use App\Models\Status;
use App\Models\Program;
use App\Forms\RatesForm;
use App\Forms\TermsForm;
use App\Models\ApptType;
use App\Forms\GenderForm;
use App\Forms\StatusForm;
use App\Models\Countries;
use App\Models\RatesView;
use App\Models\StaffType;
use App\Models\Speedcodes;
use App\Tables\RatesTable;
use App\Forms\ProgramsForm;
use App\Models\FinanceTeam;
use App\Models\ProjectTeam;
use App\Models\Immigration;
use App\Forms\CountriesForm;
use App\Forms\StaffTypeForm;
use App\Models\StaffSubtype;
use Illuminate\Http\Request;
use App\Forms\Appt_typesForm;
use App\Forms\SpeedcodesForm;
use App\Forms\FinanceTeamForm;
use App\Forms\ProjectTeamForm;
use App\Forms\ImmigrationForm;
use App\Forms\StaffSubtypeForm;
use App\Models\ProjectPriority;
use App\Models\ProjectEvaluation;
use App\Forms\ProjectPriorityForm;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\Splade\SpladeForm;
use App\Forms\ProjectEvaluationForm;
use ProtoneMedia\Splade\SpladeTable;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\FormBuilder\Date;
use ProtoneMedia\Splade\FormBuilder\Input;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;
use ProtoneMedia\Splade\FormBuilder\Checkbox;
use ProtoneMedia\Splade\FormBuilder\Textarea;

class ReferenceController extends Controller
{
    public function index() {
        $genderTable = SpladeTable::for(Gender::all())
            ->name('genders')
            ->column('gender')
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'genders', 'item' => $item->gender]);
            });

        $immigrationTable = SpladeTable::for(Immigration::all())
            ->name('immigration')
            ->column('code')
            ->column('description')
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'immigration', 'item' => $item->code]);
            });

        $termQuery = Terms::query()->paginate(request('perPage', 15), ['*'], 'terms');
        $terms = SpladeTable::for($termQuery)
            ->name('terms')
            ->column('identifier', sortable: true)
            ->column('semester', sortable: true)
            ->column('year', sortable: true)
            ->column('number', 'Term Number', sortable: true)
            ->column('start', sortable: true)
            ->column('end', sortable: true)
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'terms', 'item' => $item->identifier]);
            });

        $ratesQuery = RatesView::query()->paginate(request('perPage', 15), ['*'], 'rates');
        $rates = SpladeTable::for($ratesQuery)
            ->name('rates')
            ->column('term', sortable: true)
            ->column('program', sortable: true)
            ->column('program_year', sortable: true)
            ->column('salary_step', sortable: true)
            ->column('rate_type', sortable: true)
            ->column('immigration', sortable: true)
            ->column('cs_award', sortable: true, as: fn($item, $rate) => sprintf('$%.02f', $item))
            ->column('cs_salary', sortable: true, as: fn($item, $rate) => sprintf('$%.02f', $item))
            ->column('amii_topup', sortable: true, as: fn($item, $rate) => sprintf('$%.02f', $item))
            ->column('int_idf', 'International IDF', sortable: true, as: fn($item, $rate) => sprintf('$%.02f', $item))
            ->column('int_amii', 'International Amii', sortable: true, as: fn($item, $rate) => sprintf('$%.02f', $item))
            ->column('rate_amount', sortable: true, as: fn($item, $rate) => sprintf('$%.02f', $item))
            ->column('rate_code', sortable: true)
            ->column('notes')
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'rates', 'item' => $item->id]);
            });
            
        $countries = SpladeTable::for(Countries::paginate(request('perPage', 15), ['*'], 'country'))
            ->name('countries')
            ->column('country')
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'countries', 'item' => $item->country]);
            });

        $programs = SpladeTable::for(Program::all())
            ->name('programs')
            ->column('program')
            ->column('description')
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'programs', 'item' => $item->program]);
            });

        $appt_types = SpladeTable::for(ApptType::all())
            ->name('apptTypes')
            ->column('name')
            ->column('description')
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'appt_types', 'item' => $item->id]);
            });

        $staff_types = SpladeTable::for(StaffType::all())
            ->name('staffTypes')
            ->column('name')
            ->column('description')
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'staff_types', 'item' => $item->id]);
            });

        $staff_subtypes = SpladeTable::for(StaffSubtype::all())
            ->name('staffSubtypes')
            ->column('name')
            ->column('description')
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'staff_subtypes', 'item' => $item->id]);
            });

        $status = SpladeTable::for(Status::all())
            ->name('Statuses')
            ->column('name')
            ->column('description')
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'status', 'item' => $item->name]);
            });

        $finance = SpladeTable::for(FinanceTeam::all())
            ->name('financeTeam')
            ->column('name')
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'finance_team', 'item' => $item->name]);
            });

        $project_team = SpladeTable::for(ProjectTeam::with('user')->get())
            ->name('projectTeam')
            ->column('user.name', 'Name')
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'project_team', 'item' => $item->id]);
            });

        $proj_prio = SpladeTable::for(ProjectPriority::all())
            ->name('projectPriority')
            ->column('priority')
            ->column('text_colour')
            ->column('bg_colour')
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'project_priority', 'item' => $item->id]);
            });

        $proj_eval = SpladeTable::for(ProjectEvaluation::all())
            ->name('projectEvaluation')
            ->column('status')
            ->column('text_colour')
            ->column('bg_colour')
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'project_evaluation', 'item' => $item->id]);
            });

        return view('extra.index', [
            'genders' => $genderTable,
            'immigration' => $immigrationTable,
            'terms' => $terms,
            'rates' => $rates,
            'countries' => $countries,
            'programs' => $programs,
            'appt_types' => $appt_types,
            'staff_types' => $staff_types,
            'staff_subtypes' => $staff_subtypes,
            'status' => $status,
            'finance_team' => $finance,
            'project_team' => $project_team,
            'project_priority' => $proj_prio,
            'project_evaluation' => $proj_eval,
        ]);
    }

    public function rates() {
        $rates = RatesTable::class;

        return view('extra.rates', [
            'rates' => $rates,
        ]);
    }

    private function getForm($table) {
        $class = null;
        switch($table) {
            case 'terms':
                $class = TermsForm::class;
                break;
            case 'rates':
                $class = RatesForm::class;
                break;
            case 'genders':
                $class = GenderForm::class;
                break;
            case 'immigration':
                $class = ImmigrationForm::class;
                break;
            case 'countries':
                $class = CountriesForm::class;
                break;
            case 'programs':
                $class = ProgramsForm::class;
                break;
            case 'appt_types':
                $class = Appt_typesForm::class;
                break;
            case 'staff_subtypes':
                $class = StaffSubtypeForm::class;
                break;
            case 'staff_types':
                $class = StaffTypeForm::class;
                break;
            case 'status':
                $class = StatusForm::class;
                break;
            case 'finance_team':
                $class = FinanceTeamForm::class;
                break;
            case 'project_team':
                $class = ProjectTeamForm::class;
                break;
            case 'project_priority':
                $class = ProjectPriorityForm::class;
                break;
            case 'project_evaluation':
                $class = ProjectEvaluationForm::class;
                break;
        }
        return $class;
    }

    private function getModel($table) {
        $class = null;
        switch($table) {
            case 'terms':
                $class = Terms::class;
                break;
            case 'rates':
                $class = Rates::class;
                break;
            case 'genders':
                $class = Gender::class;
                break;
            case 'immigration':
                $class = Immigration::class;
                break;
            case 'countries':
                $class = Countries::class;
                break;
            case 'programs':
                $class = Program::class;
                break;
            case 'appt_types':
                $class = ApptType::class;
                break;
            case 'staff_subtypes':
                $class = StaffSubtype::class;
                break;
            case 'staff_types':
                $class = StaffType::class;
                break;
            case 'status':
                $class = Status::class;
                break;
            case 'finance_team':
                $class = FinanceTeam::class;
                break;
            case 'project_team':
                $class = ProjectTeam::class;
                break;
            case 'project_priority':
                $class = ProjectPriority::class;
                break;
            case 'project_evaluation':
                $class = ProjectEvaluation::class;
                break;
        }
        return $class;
    }

    public function create($table) {
        $fields = array();
        $form = call_user_func([$this->getForm($table), 'make'])
            ->fields([
                Submit::make()->label('Add'),
            ]);
        return view('extra.create', [
            'form' => $form
        ]);
    }

    public function store($table, Request $request) {
        $class = $this->getForm($table);
        $input = $request->validate((new $class())->rules());
        if(empty($input)) {
            Toast::title('Error')->message('Error adding item!')->danger()->autoDismiss(10);
        }
        else {
            call_user_func([$this->getModel($table), 'updateOrCreate'], $input);
            Action::create([
                'user' => $request->user()->name,
                'table' => $table,
                'from' => '{}',
                'to' => json_encode($input)
            ]);
        }
        return redirect()->back();
    }

    public function edit($table, $item) {
        $class = $this->getForm($table);
        $form = call_user_func([$class, 'make'])
            ->action(route('reference.update', ['table' => $table, 'item' => $item]))
            ->fields([
                Submit::make()->label('Update'),
            ]);
        $val = null;
        $val = call_user_func([$this->getModel($table), 'find'], $item);
        $form->fill($val);
        return view('extra.edit', [
            'form' => $form
        ]);
    }

    public function update($table, $item, Request $request) {
        $class = $this->getForm($table);
        $input = $request->validate((new $class())->rules());
        if(empty($input)) {
            Toast::title('Error')->message('Error editing item!')->danger()->autoDismiss(10);
        }
        else {
            $val = call_user_func([$this->getModel($table), 'find'], $item);
            $val->fill($input);
            $val->save();
        }
        return redirect()->back();
    }
}
