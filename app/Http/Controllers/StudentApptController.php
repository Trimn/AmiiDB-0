<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Rates;
use App\Models\Terms;
use App\Models\People;
use App\Models\Program;
use App\Models\Student;
use App\Models\ApptType;
use App\Models\RatesView;
use App\Models\StudentAppt;
use App\Models\Immigration;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Tables\ClaimedStudents;
use App\Forms\CreateStudentApptForm;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreStudentApptRequest;
use App\Http\Requests\UpdateStudentApptRequest;

class StudentApptController extends Controller
{
    private const RATE_FILTER_COOKIE_KEY = 'student_appt_rate_filters';

    private function normalizeRateFilters(array $filters): array
    {
        return [
            'rate_filter_term' => trim((string) ($filters['rate_filter_term'] ?? $filters['term'] ?? '')),
            'rate_filter_program' => trim((string) ($filters['rate_filter_program'] ?? $filters['program'] ?? '')),
            'rate_filter_salary_step' => trim((string) ($filters['rate_filter_salary_step'] ?? $filters['salary_step'] ?? '')),
            'rate_filter_immigration' => trim((string) ($filters['rate_filter_immigration'] ?? $filters['immigration'] ?? '')),
            'rate_filter_rate_type' => trim((string) ($filters['rate_filter_rate_type'] ?? $filters['rate_type'] ?? '')),
        ];
    }

    private function getRateFilterDefaults(array $fallback = []): array
    {
        $fallback = $this->normalizeRateFilters($fallback);
        $cookieRaw = request()->cookie(self::RATE_FILTER_COOKIE_KEY, '');
        $cookieFilters = is_string($cookieRaw) ? json_decode($cookieRaw, true) : [];
        $persisted = $this->normalizeRateFilters(is_array($cookieFilters) ? $cookieFilters : []);

        // Persisted values win, so filters remain stable across modal opens.
        return array_merge($fallback, $persisted);
    }

    private function withAllOption(array $options): array
    {
        return ['' => 'All'] + $options;
    }

    private function rateFilterOptions(): array
    {
        $rateTypes = array_combine(array_keys(Rates::options()), array_keys(Rates::options()));

        return [
            'terms' => $this->withAllOption(Terms::options()),
            'programs' => $this->withAllOption(Program::options()),
            'salarySteps' => $this->withAllOption(RatesView::query()
                ->select('salary_step')
                ->whereNotNull('salary_step')
                ->where('salary_step', '!=', '')
                ->distinct()
                ->orderBy('salary_step')
                ->pluck('salary_step', 'salary_step')
                ->toArray()),
            'immigration' => $this->withAllOption(Immigration::options()),
            'rateTypes' => $this->withAllOption($rateTypes),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function claimedStudents()
    {
        return view('studentappts.claimed', [
            'appts' => ClaimedStudents::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Student $student)
    {
        return view('students.appointments.create', [
            'student' => $student,
            'rateFilterDefaults' => $this->getRateFilterDefaults(),
            'terms' => Terms::options(),
            'apptTypes' => ApptType::options(),
            'rateFilters' => $this->rateFilterOptions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStudentApptRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentApptRequest $request, Student $student)
    {
        $input = $request->validated();
        StudentAppt::updateOrCreate([
            'sid' => $student->id,
            'term' => $input['term'],
            'start' => $input['start'],
            'end' => $input['end'],
            'appt_type' => $input['appt_type'],
            'speedcode_1' => $input['speedcode_1'],
            'speedcode_2' => $input['speedcode_2'],
            'speedcode_3' => $input['speedcode_3'],
            'speedcode_1_prc' => $input['speedcode_1_prc'],
            'speedcode_2_prc' => $input['speedcode_2_prc'],
            'speedcode_3_prc' => $input['speedcode_3_prc'],
            'rate' => $input['rate'],
            'rate_adj' => $input['rate_adj'],
            'eform' => $input['eform'],
        ]);
        return redirect()->route('people.show', ['person' => $student->pid]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentAppt  $studentAppt
     * @return \Illuminate\Http\Response
     */
    public function show(StudentAppt $studentAppt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentAppt  $studentAppt
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentAppt $appt)
    {
        $appt->load('rate_view');

        $fallbackRateFilters = [
            'rate_filter_term' => $appt->rate_view->term ?? '',
            'rate_filter_program' => $appt->rate_view->program ?? '',
            'rate_filter_salary_step' => $appt->rate_view->salary_step ?? '',
            'rate_filter_immigration' => $appt->rate_view->immigration ?? '',
            'rate_filter_rate_type' => $appt->rate_view->rate_type ?? '',
        ];

        $formDefaults = array_merge($appt->toArray(), [
            ...$this->getRateFilterDefaults($fallbackRateFilters),
        ]);

        return view('students.appointments.edit', [
            'appt' => $appt,
            'formDefaults' => $formDefaults,
            'terms' => Terms::options(),
            'apptTypes' => ApptType::options(),
            'rateFilters' => $this->rateFilterOptions(),
        ]);
    }

    public function rateOptions(Request $request)
    {
        $normalizedFilters = $this->normalizeRateFilters($request->query());

        $query = RatesView::query()
            ->orderBy('term')
            ->orderBy('program')
            ->orderBy('salary_step')
            ->orderBy('immigration')
            ->orderBy('rate_type')
            ->get(['id', 'rate_code', 'term', 'program', 'salary_step', 'immigration', 'rate_type']);

        $filters = [
            'term' => $normalizedFilters['rate_filter_term'],
            'program' => $normalizedFilters['rate_filter_program'],
            'salary_step' => $normalizedFilters['rate_filter_salary_step'],
            'immigration' => $normalizedFilters['rate_filter_immigration'],
            'rate_type' => $normalizedFilters['rate_filter_rate_type'],
        ];

        $rates = $query->filter(function ($rate) use ($filters) {
            foreach ($filters as $key => $value) {
                if ($value !== '' && (string) $rate->{$key} !== $value) {
                    return false;
                }
            }
            return true;
        })->map(function ($rate) {
            return [
                'value' => (string) $rate->id,
                'label' => $rate->rate_code,
            ];
        })->values();

        return response()
            ->json($rates)
            ->cookie(cookie()->forever(
                self::RATE_FILTER_COOKIE_KEY,
                json_encode($normalizedFilters, JSON_UNESCAPED_SLASHES)
            ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStudentApptRequest  $request
     * @param  \App\Models\StudentAppt  $studentAppt
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentApptRequest $request, StudentAppt $appt)
    {
        $input = $request->validated();
        $appt->fill($input);
        $appt->save();
        return redirect()->route('people.show', ['person' => $appt->student->pid]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentAppt  $studentAppt
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentAppt $appt)
    {
        if(Auth::user()->can('delete')) {
            $appt->delete();
        }
        return redirect()->back();
    }

    public function allActiveAppts() {
        $appts = StudentAppt::whereRelation('student', 'active', 'ACTIVE')
            ->with(['student', 'student.person', 'appt', 'rate_view'])
            ->get()
            ->toArray();

        $appts = Arr::map($appts, function ($item) {
            return Arr::dot($item);
        });

        $columns = [
            'student.person.uid' => 'uid',
            'student.person.first_name' => 'first_name',
            'student.person.last_name' => 'last_name',
            'student.person.ccid' => 'ccid',
            'student.active' => 'active',
            'term' => 'term',
            'start' => 'start',
            'end' => 'end',
            'speedcode_1' => 'speedcode_1',
            'speedcode_1_prc' => 'speedcode_1_prc',
            'speedcode_2' => 'speedcode_2',
            'speedcode_2_prc' => 'speedcode_2_prc',
            'speedcode_3' => 'speedcode_3',
            'speedcode_3_prc' => 'speedcode_3_prc',
            'rate_view.rate_code' => 'rate_code',
            'rate_view.rate_amount' => 'rate_amount',
            'rate_adj' => 'rate_adj',
        ];

        $appts = Arr::map($appts, function ($item) use ($columns) {
            $res = array();
            foreach($columns as $column => $alias) {
                if(array_key_exists($column, $item)) {
                    $res[$alias] = $item[$column];
                }
                else {
                    $res[$alias] = null;
                }
            }
            $res['total_pay'] = $res['rate_amount'] + $res['rate_adj'];
            $res['pay_per_month'] = $res['total_pay'] / (Carbon::parse($res['start'])->diffInMonths(Carbon::parse($res['end'])) + 1);
            $res['ppm_sc1'] = $res['pay_per_month'] * ($res['speedcode_1_prc'] ? $res['speedcode_1_prc'] : 0)/100;
            $res['ppm_sc2'] = $res['pay_per_month'] * ($res['speedcode_2_prc'] ? $res['speedcode_2_prc'] : 0)/100;
            $res['ppm_sc3'] = $res['pay_per_month'] * ($res['speedcode_3_prc'] ? $res['speedcode_3_prc'] : 0)/100;
            return $res;
        });
        return $appts;
    }
}
