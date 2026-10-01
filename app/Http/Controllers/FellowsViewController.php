<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Models\Budget;
use App\Models\Etrac;
use App\Models\Fellows;
use App\Models\People;
use App\Models\Projects;
use App\Models\Salary;
use App\Models\Speedcodes;
use App\Tables\FellowStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Tables\FellowStaffAll;
use App\Tables\FellowStudents;
use App\Tables\FellowSpeedcodes;
use App\Tables\ForecastingStaff;
use App\Tables\FellowStudentsAll;
use App\Tables\ForecastingStudents;
use App\Exports\BudgetAllExport;
use Maatwebsite\Excel\Facades\Excel;

class FellowsViewController extends Controller
{
    private function viewAsFellowName(): ?string {
        $user = Auth::user();
        if (!$user || !$user->hasAnyRole(['admin', 'super admin'])) {
            return null;
        }

        $viewAsFellowId = session('view_as_fellow_id');
        if (!$viewAsFellowId) {
            return null;
        }

        $viewAsFellow = Fellows::with('person')->find($viewAsFellowId);
        if (!$viewAsFellow) {
            session()->forget('view_as_fellow_id');
            return null;
        }

        return $viewAsFellow->name();
    }

    private function withViewAsContext(array $data): array {
        $data['viewAsFellowName'] = $this->viewAsFellowName();
        $data['viewAsFellowId'] = session('view_as_fellow_id');
        $data['viewAsFellowOptions'] = !empty($data['viewAsFellowName']) ? Fellows::optionsAll() : [];
        return $data;
    }

    private function resolveEffectiveFellow(): ?Fellows {
        $user = Auth::user();

        if ($user && $user->hasAnyRole(['admin', 'super admin'])) {
            $viewAsFellowId = session('view_as_fellow_id');
            if ($viewAsFellowId) {
                $viewAsFellow = Fellows::find($viewAsFellowId);
                if ($viewAsFellow) {
                    return $viewAsFellow;
                }

                session()->forget('view_as_fellow_id');
            }
        }

        $fellow = Fellows::findFellow($user);
        if ($fellow) {
            return $fellow;
        }

        if ($user && $user->can('edit')) {
            return Fellows::first();
        }

        return null;
    }

    private function requireEffectiveFellow(): Fellows {
        $fellow = $this->resolveEffectiveFellow();
        if (!$fellow) {
            abort(403);
        }

        return $fellow;
    }

    public function staffIndex() {
        $fellow = $this->requireEffectiveFellow();
        $data = ['fellow' => $fellow];
        $data['fellowStudents'] = new FellowStudents($fellow);
        $data['fellowStaff'] = new FellowStaff($fellow);
        return view('fellow.index', $this->withViewAsContext($data));
    }

    public function allStaffIndex() {
        $fellow = $this->requireEffectiveFellow();
        $data = ['fellow' => $fellow];
        $data['fellowStudents'] = new FellowStudentsAll($fellow);
        $data['fellowStaff'] = new FellowStaffAll($fellow);
        return view('fellow.index', $this->withViewAsContext($data));
    }

    public function speedcodesIndex() {
        $fellow = $this->requireEffectiveFellow();
        $data = ['fellow' => $fellow];

        $data['speedcodes'] = new FellowSpeedcodes($fellow);
        return view('fellow.speedcodes', $this->withViewAsContext($data));
    }

    public function viewSpeedcode(Projects $project) {
        $end = Carbon::now()->addYears(3)->format('Y-m-d');
        $data = [
            'project' => $project,
            'students' => new ForecastingStudents($project, $end),
            'staff' => new ForecastingStaff($project, $end),
            'est_students' => Projects::student_commitment($project, $end),
            'est_staff' => Projects::staff_commitment($project, $end),
        ];
        return view('fellow.edit', $this->withViewAsContext($data));
    }

    public function updateProject(Projects $project, Request $request) {
        $input = $request->validate(['notes' => 'nullable']);
        $project['notes'] = $input['notes'];
        $project->save();
        return redirect()->back();
    }

    /**
     * Compute FY start and end dates for a project and FY year.
     * Uses the project's fy_start/fy_end to determine month/day boundaries.
     * Falls back to April 1 - March 31 if not set.
     */
    private function computeFiscalYear(Projects $project, int $fyYear): array {
        $startMonth = $project->fy_start ? Carbon::parse($project->fy_start)->month : 4;
        $startDay   = $project->fy_start ? Carbon::parse($project->fy_start)->day : 1;
        $endMonth   = $project->fy_end ? Carbon::parse($project->fy_end)->month : 3;
        $endDay     = $project->fy_end ? Carbon::parse($project->fy_end)->day : 31;

        $fyStart = Carbon::create($fyYear, $startMonth, $startDay)->format('Y-m-d');
        // If end month < start month, FY end is in the next calendar year
        $endYear = $endMonth < $startMonth ? $fyYear + 1 : $fyYear;
        $fyEnd = Carbon::create($endYear, $endMonth, $endDay)->format('Y-m-d');

        return [$fyStart, $fyEnd];
    }

    /**
     * Determine the current FY year for a project based on its fy_start/fy_end boundaries.
     */
    private function currentFyYearForProject(Projects $project): int {
        $now = Carbon::now();
        $startMonth = $project->fy_start ? Carbon::parse($project->fy_start)->month : 4;
        $endMonth   = $project->fy_end ? Carbon::parse($project->fy_end)->month : 3;
        $endDay     = $project->fy_end ? Carbon::parse($project->fy_end)->day : 31;

        // The FY end date in the current calendar year
        $fyEndThisYear = Carbon::create($now->year, $endMonth, $endDay);

        if ($endMonth < $startMonth) {
            // FY wraps across calendar years (e.g. Apr-Mar, May-Apr)
            return $now->lte($fyEndThisYear) ? $now->year - 1 : $now->year;
        } else {
            // FY within a single calendar year (e.g. Jan-Dec)
            return $now->lte($fyEndThisYear) ? $now->year : $now->year + 1;
        }
    }

    /**
     * Apply strict salary program filtering for budget views.
     */
    private function applySalaryProgramFilter($query, $program) {
        if (is_null($program) || $program === '') {
            return $query;
        }

        return $query->whereRaw('TRIM(program) = ?', [trim((string) $program)]);
    }

    /**
     * Resolve optional from/to custom date range query parameters (YYYY-MM-DD).
     * Used to restrict budget actuals to a sub-range within the selected FY.
     * Returns [$dateFrom, $dateTo] (either may be null).
     */
    private function resolveCustomDateRange(): array {
        $from = request()->query('from');
        $to = request()->query('to');
        $dateFrom = (is_string($from) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) ? $from : null;
        $dateTo = (is_string($to) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) ? $to : null;
        return [$dateFrom, $dateTo];
    }

    /**
     * Clamp a project's FY date range to an optional custom sub-range (from/to).
     * Returns [$effStart, $effEnd]; $effStart may be later than $effEnd if there
     * is no overlap with the FY (callers should treat that as "no data").
     */
    private function clampFyDates(string $fyStart, string $fyEnd, ?string $dateFrom, ?string $dateTo): array {
        $effStart = ($dateFrom && $dateFrom > $fyStart) ? $dateFrom : $fyStart;
        $effEnd = ($dateTo && $dateTo < $fyEnd) ? $dateTo : $fyEnd;
        return [$effStart, $effEnd];
    }

    /**
     * Determine whether a speedcode/project should be listed for the given FY.
     * A project that only starts after the FY end date is excluded for that FY
     * (it has no spending period within the FY). Projects with no start date
     * are kept (existing behaviour).
     */
    private function projectActiveInFy(Speedcodes $sc, string $fyEnd): bool {
        if (empty($sc->award_start)) {
            return true;
        }
        try {
            return Carbon::parse($sc->award_start)->startOfDay()->lte(Carbon::parse($fyEnd)->endOfDay());
        } catch (\Throwable $e) {
            return true;
        }
    }

    /**
     * Find distinct FY years that have salary or etrac data for a given project number,
     * using the project's FY start month to determine which FY year a record belongs to.
     */
    private function availableFYsForProject(string $projectNumber, int $fyStartMonth, ?string $program = null): array {
        $salaryQuery = Salary::query()
            ->select(DB::raw("IF(MONTH(posting_date) >= {$fyStartMonth}, YEAR(posting_date), YEAR(posting_date) - 1) as fy"))
            ->where('project', '=', $projectNumber)
            ->where(function ($query) {
                $query->where('actual', '!=', 0)
                    ->orWhere('commitment', '!=', 0);
            })
            ->groupBy('fy');
            
        $this->applySalaryProgramFilter($salaryQuery, $program);
        
        $salaryFYs = $salaryQuery->pluck('fy')->toArray();

        $etracQuery = Etrac::query()
            ->select(DB::raw("IF(MONTH(date) >= {$fyStartMonth}, YEAR(date), YEAR(date) - 1) as fy"))
            ->where('project', '=', $projectNumber)
            ->whereNot('category', 'LIKE', '%Salaries%')
            ->groupBy('fy')
            ->havingRaw('ROUND(SUM(actual), 2) != 0');
            
        if ($program) {
            $etracQuery->where('program', '=', $program);
        }
        
        $etracFYs = $etracQuery->pluck('fy')->toArray();

        return collect(array_merge($salaryFYs, $etracFYs))
            ->map(fn($v) => (int) $v)->unique()->sort()->values()->toArray();
    }

    public function budgetIndex() {
        $fellow = $this->requireEffectiveFellow();

        [$dateFrom, $dateTo] = $this->resolveCustomDateRange();

        // Match the "My Speedcodes" tab: only projects that are not Complete.
        $allSpeedcodes = Speedcodes::where('fellow', '=', $fellow->id)
            ->whereHas('projectModel', fn($q) => $q->whereNot('project_status', 'LIKE', 'Complete'))
            ->with(['projectModel', 'projectViewModel'])
            ->get();

        // Collect available FYs across all the fellow's projects, using each project's own FY boundaries
        $allAvailableFYs = collect();
        foreach ($allSpeedcodes as $sc) {
            $proj = $sc->projectModel;
            if ($proj && $sc->project) {
                $startMonth = $proj->fy_start ? Carbon::parse($proj->fy_start)->month : 4;
                $fys = $this->availableFYsForProject($sc->project, $startMonth, $proj->program);
                $allAvailableFYs = $allAvailableFYs->merge($fys);
            }
        }
        // Always include the current FY so brand-new ($0) projects are visible by default.
        $now = Carbon::now();
        $defaultCurrentFY = $now->month >= 4 ? $now->year : $now->year - 1;
        $allAvailableFYs->push($defaultCurrentFY);
        $availableFYs = $allAvailableFYs->unique()->sort()->values()->toArray();

        // Selected FY from query param (default to current)
        $selectedFY = (int) request()->query('fy', $defaultCurrentFY);
        if (!empty($availableFYs) && !in_array($selectedFY, $availableFYs)) {
            $selectedFY = in_array($defaultCurrentFY, $availableFYs) ? $defaultCurrentFY : end($availableFYs);
        }

        // Include ALL of the fellow's non-complete projects, even those with $0 actuals in the FY,
        // so the Budget Report list matches the "My Speedcodes" tab. Compute totals using the
        // selected FY clamped to any custom from/to range.
        $speedcodes = $allSpeedcodes->map(function ($sc) use ($selectedFY, $dateFrom, $dateTo) {
            $proj = $sc->projectModel;
            if (!$proj || !$sc->project) return null;

            [$fyStart, $fyEnd] = $this->computeFiscalYear($proj, $selectedFY);

            // Exclude projects that only start after the end of this FY
            if (!$this->projectActiveInFy($sc, $fyEnd)) return null;

            [$effStart, $effEnd] = $this->clampFyDates($fyStart, $fyEnd, $dateFrom, $dateTo);

            $actYtd = 0;
            if ($effStart <= $effEnd) {
                $salaryTotalQuery = Salary::where('project', '=', $sc->project)
                    ->where('posting_date', '>=', $effStart)
                    ->where('posting_date', '<=', $effEnd)
                    ->where('actual', '!=', 0);
                $this->applySalaryProgramFilter($salaryTotalQuery, $proj->program);
                $salaryTotal = $salaryTotalQuery->sum('actual');

                $etracTotal = Etrac::where('project', '=', $sc->project)
                    ->when($proj->program, fn($q) => $q->where('program', '=', $proj->program))
                    ->whereNot('category', 'LIKE', '%Salaries%')
                    ->where('date', '>=', $effStart)
                    ->where('date', '<=', $effEnd)
                    ->sum('actual');

                $actYtd = $salaryTotal + $etracTotal;
            }

            $sc->act_ytd = $actYtd;
            $sc->fy_start_date = $effStart;
            $sc->fy_end_date = $effEnd;
            $sc->funds_before = $proj->get_funds_before();
            $sc->funds_after = $proj->get_funds_after();
            $sc->commitments = $sc->funds_before - $sc->funds_after;
            $sc->open_balance_fy_start = $sc->funds_before + $sc->act_ytd;
            $sc->fy_year = $selectedFY;
            $sc->custom_from = $dateFrom;
            $sc->custom_to = $dateTo;

            return $sc;
        })->filter()->values();

        $table = new \App\Tables\FellowBudgetTable($speedcodes, $selectedFY);

        // Calculate totals for the summary below the table
        $totOpen = $speedcodes->sum('open_balance_fy_start');
        $totFuture = $speedcodes->sum(fn($sc) => $sc->projectModel->future_funding ?? 0);
        $totAct = $speedcodes->sum('act_ytd');
        $totCommit = $speedcodes->sum('commitments');
        $totFundsAfter = $speedcodes->sum('funds_after');

        return view('fellow.budget-index', $this->withViewAsContext([
            'fellow' => $fellow,
            'speedcodes' => $speedcodes,
            'fyYear' => $selectedFY,
            'availableFYs' => $availableFYs,
            'table' => $table,
            'totOpen' => $totOpen,
            'totFuture' => $totFuture,
            'totAct' => $totAct,
            'totCommit' => $totCommit,
            'totFundsAfter' => $totFundsAfter,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]));
    }

    /**
     * Build the budget report data arrays for a given project and fiscal year.
     * Returns months, salary people, category breakdowns, and totals.
     */
    private function buildReportData(Projects $project, int $fyYear, ?string $dateFrom = null, ?string $dateTo = null): array {
        // Compute FY dates using this project's FY boundaries, then clamp to optional custom range
        [$fyStart, $fyEnd] = $this->computeFiscalYear($project, $fyYear);
        [$effStart, $effEnd] = $this->clampFyDates($fyStart, $fyEnd, $dateFrom, $dateTo);

        // Build ordered month columns over the effective (clamped) range only
        $months = [];
        if ($effStart <= $effEnd) {
            $current = Carbon::parse($effStart)->startOfMonth();
            $end = Carbon::parse($effEnd);
            while ($current->lte($end)) {
                $months[$current->month] = $current->shortMonthName;
                $current->addMonth();
            }
        }

        $projectNumber = optional($project->speedcode)->project;
        $program = $project->program;

        $salaryPeople = [];
        $salaryMonthTotals = [];
        $salaryGrandTotal = 0;
        $categories = [];
        $grandMonthTotals = [];
        $grandTotal = 0;

        if ($projectNumber) {
            // --- Salary actuals grouped by uid and month ---
            $salaryQuery = Salary::query()
                ->select('uid', DB::raw('MONTH(posting_date) as month'), DB::raw('SUM(actual) as total'))
                ->where('project', '=', $projectNumber)
                ->where('posting_date', '>=', $effStart)
                ->where('posting_date', '<=', $effEnd)
                ->where('actual', '!=', 0);
            $this->applySalaryProgramFilter($salaryQuery, $program);
            $salaryData = $salaryQuery->groupBy('uid', DB::raw('MONTH(posting_date)'))
                ->havingRaw('ROUND(SUM(actual), 2) != 0')
                ->get();

            // Build per-person salary matrix
            $salaryByPerson = [];
            foreach ($salaryData as $row) {
                $uid = str_pad((string)$row->uid, 7, '0', STR_PAD_LEFT);
                if (!isset($salaryByPerson[$uid])) {
                    $salaryByPerson[$uid] = ['months' => [], 'total' => 0];
                }
                $salaryByPerson[$uid]['months'][$row->month] = $row->total;
                $salaryByPerson[$uid]['total'] += $row->total;
            }

            // Get person info for each uid
            $uids = array_keys($salaryByPerson);
            $people = People::whereIn('uid', $uids)->get()->keyBy('uid');

            // Get budget types (PhD/MSc/UG/RA) for these people on this speedcode
            $budgetTypes = Budget::where('code', '=', $project->code)
                ->whereIn('pid', $people->pluck('id'))
                ->orderBy('id', 'desc')
                ->get()
                ->keyBy('pid');

            // Pre-load student relations to get degrees efficiently
            $people->load(['student', 'staff']);

            // Build salary people array
            foreach ($salaryByPerson as $uid => $data) {
                $person = $people->get($uid);
                
                // Get the budget type, using the most recent budget entry for this person & project
                $budgetType = $person ? (optional($budgetTypes->get($person->id))->type ?? '') : '';
                
                // If we couldn't find a budget type on this specific project, try to get their general student degree/program
                if (empty($budgetType) && $person && $person->student) {
                    // Access the raw attribute to avoid triggering the relation which expects a 'student_id' column
                    $programVal = $person->student->getAttributes()['program'] ?? null;
                    if (is_string($programVal) && !empty($programVal)) {
                        $budgetType = $programVal;
                    } elseif (is_numeric($programVal)) {
                        $programModel = \App\Models\Program::find($programVal);
                        if ($programModel) {
                            $budgetType = $programModel->name;
                        }
                    }
                }
                
                // If it's still empty, check if they are Staff
                if (empty($budgetType) && $person && $person->staff) {
                    // For staff, we might want their pos_type (which links to StaffType) or job_title
                    $posTypeVal = $person->staff->getAttributes()['pos_type'] ?? null;
                    if (is_numeric($posTypeVal)) {
                        $staffTypeModel = \App\Models\StaffType::find($posTypeVal);
                        if ($staffTypeModel) {
                            $budgetType = $staffTypeModel->type;
                        }
                    } elseif (is_string($posTypeVal) && !empty($posTypeVal)) {
                        $budgetType = $posTypeVal;
                    }
                    
                    if (empty($budgetType)) {
                        $budgetType = $person->staff->getAttributes()['job_title'] ?? 'Staff';
                    }
                }
                
                // If it's still empty, try to grab from their general budget entries under other speedcodes
                if (empty($budgetType) && $person) {
                    $anyBudget = Budget::where('pid', $person->id)->whereNotNull('type')->where('type', '!=', '')->orderBy('id', 'desc')->first();
                    if ($anyBudget && $anyBudget->type) {
                        $budgetType = $anyBudget->type;
                    }
                }
                
                // If it's still empty, see if they have a non-student type like Affiliate
                if (empty($budgetType) && $person && $person->affiliate) {
                    $budgetType = 'Affiliate';
                }

                $salaryPeople[] = [
                    'uid' => $uid,
                    'name' => $person ? ($person->last_name . ', ' . $person->first_name) : $uid,
                    'type' => $budgetType,
                    'months' => $data['months'],
                    'total' => $data['total'],
                ];
            }

            // Salary totals per month
            foreach ($salaryPeople as $person) {
                foreach ($person['months'] as $month => $amount) {
                    $salaryMonthTotals[$month] = ($salaryMonthTotals[$month] ?? 0) + $amount;
                }
                $salaryGrandTotal += $person['total'];
            }

            // --- Non-salary etrac actuals grouped by category and month ---
            $etracQuery = Etrac::query()
                ->select('category', DB::raw('MONTH(date) as month'), DB::raw('SUM(actual) as total'))
                ->where('project', '=', $projectNumber)
                ->where('date', '>=', $effStart)
                ->where('date', '<=', $effEnd)
                ->whereNot('category', 'LIKE', '%Salaries%')
                ->where('actual', '!=', 0);
            if ($program) {
                $etracQuery->where('program', '=', $program);
            }
            $etracData = $etracQuery->groupBy('category', DB::raw('MONTH(date)'))
                ->havingRaw('ROUND(SUM(actual), 2) != 0')
                ->get();

            foreach ($etracData as $row) {
                $cat = $row->category;
                if (!isset($categories[$cat])) {
                    $categories[$cat] = ['months' => [], 'total' => 0];
                }
                $categories[$cat]['months'][$row->month] = ($categories[$cat]['months'][$row->month] ?? 0) + $row->total;
                $categories[$cat]['total'] += $row->total;
            }

            // Grand totals per month (salary + all non-salary categories)
            $grandMonthTotals = $salaryMonthTotals;
            $grandTotal = $salaryGrandTotal;
            foreach ($categories as $data) {
                foreach ($data['months'] as $month => $amount) {
                    $grandMonthTotals[$month] = ($grandMonthTotals[$month] ?? 0) + $amount;
                }
                $grandTotal += $data['total'];
            }

            // Find the last transaction date across salary and etrac
            $lastSalaryDate = $effStart <= $effEnd ? Salary::where('project', '=', $projectNumber)
                ->where('posting_date', '>=', $effStart)
                ->where('posting_date', '<=', $effEnd)
                ->max('posting_date') : null;

            $lastEtracDate = $effStart <= $effEnd ? Etrac::where('project', '=', $projectNumber)
                ->where('date', '>=', $effStart)
                ->where('date', '<=', $effEnd)
                ->max('date') : null;

            $lastTransactionDate = collect([$lastSalaryDate, $lastEtracDate])
                ->filter()
                ->map(fn($d) => Carbon::parse($d))
                ->max();
        }

        return [
            'months' => $months,
            'salaryPeople' => $salaryPeople,
            'salaryMonthTotals' => $salaryMonthTotals,
            'salaryGrandTotal' => $salaryGrandTotal,
            'categories' => $categories,
            'grandMonthTotals' => $grandMonthTotals,
            'grandTotal' => $grandTotal,
            'fyStart' => $effStart,
            'fyEnd' => $effEnd,
            'originalFyStart' => $fyStart,
            'originalFyEnd' => $fyEnd,
            'lastTransactionDate' => $lastTransactionDate ?? null,
        ];
    }

    public function budgetReport(Projects $project) {
        $fellow = $this->requireEffectiveFellow();

        // All fellow's projects for tabs
        $allSpeedcodes = Speedcodes::where('fellow', '=', $fellow->id)
            ->whereHas('projectModel', fn($q) => $q->whereNot('project_status', 'LIKE', 'Complete'))
            ->with('projectModel')
            ->get();

        // Determine current FY year for this project
        $currentFyYear = $this->currentFyYearForProject($project);

        // Accept ?fy=YYYY (numeric) or legacy current/previous
        $fyParam = request()->query('fy', (string) $currentFyYear);
        if ($fyParam === 'current') {
            $fyYear = $currentFyYear;
        } elseif ($fyParam === 'previous') {
            $fyYear = $currentFyYear - 1;
        } else {
            $fyYear = (int) $fyParam;
        }

        // Filter the project tabs: only projects active during this FY (award_start <= FY end),
        // using each project's own FY boundaries.
        $speedcodes = $allSpeedcodes->filter(function ($sc) use ($fyYear) {
            $proj = $sc->projectModel;
            if (!$proj) return false;
            [, $fyEnd] = $this->computeFiscalYear($proj, $fyYear);
            return $this->projectActiveInFy($sc, $fyEnd);
        })->values();

        // Find available FYs for this project
        $projectNumber = optional($project->speedcode)->project;
        $startMonth = $project->fy_start ? Carbon::parse($project->fy_start)->month : 4;
        $availableFYs = $projectNumber ? $this->availableFYsForProject($projectNumber, $startMonth, $project->program) : [];

        // Optional custom date range (clamps the FY bounds)
        [$dateFrom, $dateTo] = $this->resolveCustomDateRange();

        // Build the report data
        $reportData = $this->buildReportData($project, $fyYear, $dateFrom, $dateTo);

        // Calculate summary figures
        $actYtd = $reportData['grandTotal'] ?? 0;
        $fundsBefore = $project->get_funds_before();
        $fundsAfter = $project->get_funds_after();
        $commitments = $fundsBefore - $fundsAfter;
        $openBalanceFyStart = $fundsBefore + $actYtd;
        $futureFunding = $project->future_funding ?? 0;

        return view('fellow.budget-report', $this->withViewAsContext(array_merge($reportData, [
            'project' => $project,
            'fellow' => $fellow,
            'speedcodes' => $speedcodes,
            'fyYear' => $fyYear,
            'availableFYs' => $availableFYs,
            'actYtd' => $actYtd,
            'fundsBefore' => $fundsBefore,
            'fundsAfter' => $fundsAfter,
            'commitments' => $commitments,
            'openBalanceFyStart' => $openBalanceFyStart,
            'futureFunding' => $futureFunding,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ])));
    }

    /**
     * Export all budget reports for the fellow's projects as an Excel workbook.
     */
    public function budgetExportAll(Request $request) {
        $fellow = $this->requireEffectiveFellow();

        // Match the "My Speedcodes" tab: only projects that are not Complete.
        $allSpeedcodes = Speedcodes::where('fellow', '=', $fellow->id)
            ->whereHas('projectModel', fn($q) => $q->whereNot('project_status', 'LIKE', 'Complete'))
            ->with(['projectModel', 'projectViewModel'])
            ->get();

        // Determine current FY using a default April-March boundary
        $now = Carbon::now();
        $defaultCurrentFY = $now->month >= 4 ? $now->year : $now->year - 1;
        $selectedFY = (int) $request->query('fy', $defaultCurrentFY);

        // Optional custom date range (clamps each project's FY bounds)
        [$dateFrom, $dateTo] = $this->resolveCustomDateRange();

        // Include all non-complete projects (even those with $0 actuals), matching My Speedcodes,
        // but exclude projects that only start after the end of the selected FY.
        $speedcodes = $allSpeedcodes->filter(function ($sc) use ($selectedFY) {
            $proj = $sc->projectModel;
            if (!$proj || !$sc->project) return false;
            [, $fyEnd] = $this->computeFiscalYear($proj, $selectedFY);
            return $this->projectActiveInFy($sc, $fyEnd);
        })->values();

        // Build report data for each project
        $sheetsData = [];
        foreach ($speedcodes as $sc) {
            $proj = $sc->projectModel;
            if (!$proj) continue;

            $sheetsData[] = [
                'speedcode' => $sc->code,
                'projectName' => $sc->description ?? '',
                'reportData' => $this->buildReportData($proj, $selectedFY, $dateFrom, $dateTo),
            ];
        }

        $filename = 'budget-report-FY' . $selectedFY . '-' . substr($selectedFY + 1, 2) . '.xlsx';

        return Excel::download(new BudgetAllExport($selectedFY, $sheetsData), $filename);
    }
}
