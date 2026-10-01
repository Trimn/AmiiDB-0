<?php

namespace App\Models;

use App\Models\Salary;
use App\Models\Loggable;
use App\Models\ProjectsView;
use App\Models\ProjectPriority;
use App\Models\ProjectEvaluation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kirschbaum\PowerJoins\PowerJoins;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Projects extends Model
{
    use HasFactory, Loggable, PowerJoins;

    protected $table = 'projects';

    protected $fillable = [
        'code',
        'total_award',
        'funds_before',
        'funds_after',
        'future_funding',
        'ff_start',
        'ff_end',
        'ff_verified',
        'project_status',
        'balance_alert',
        'percent_spent',
        'oe_status',
        'auth_oe_amount',
        'oe_auth_end',
        'oe_req_status',
        'financial_report',
        'supervisor_review',
        'who_assigned',
        'priority_id',
        'eval_status_id',
        'notes',
        'opening_balance',
        'program',
        'fy_start',
        'fy_end',
        'fy_override',
    ];

    protected $casts = [
        'ff_verified' => 'boolean',
        'financial_report' => 'boolean',
        'supervisor_review' => 'boolean',
        'fy_override' => 'boolean',
    ];

    public function speedcode(): HasOne {
        return $this->hasOne(Speedcodes::class, 'code', 'code');
    }

    public function assigned(): HasOne {
        return $this->hasOne(FinanceTeam::class, 'id', 'who_assigned');
    }

    public function priority(): HasOne {
        return $this->hasOne(ProjectPriority::class, 'id', 'priority_id');
    }

    public function eval_status(): HasOne {
        return $this->hasOne(ProjectEvaluation::class, 'id', 'eval_status_id');
    }

    public function project_view(): HasOne {
        return $this->hasOne(ProjectsView::class, 'proj_id', 'id');
    }

    public static function statuses() {
        return static::select('project_status')->distinct()->whereNotNull('project_status')->get()->pluck('project_status', 'project_status')->toArray();
    }

    public static function balanceAlertOptions(): array
    {
        return [
            'Funds Unavailable' => 'Funds Unavailable',
            'Adjustments in Progress' => 'Adjustments in Progress',
            'Restricted Budget' => 'Restricted Budget',
        ];
    }

    public static function student_commitment_query($project, $date): ?Builder {
        $lastImport = ProjectImports::select('created_at')->orderByDesc('created_at')->limit(1)->first()['created_at'];
        if(is_null($project)) {
            return null;
        }
        $start = $project['funds_before'] ?? $project['total_award'];
        // $start_date = $lastImport->format('Y-m-d');
        if(Salary::count() == 0) {
            $start_date = $lastImport->format('Y-m-d'); 
        }
        else {
            $start_date = Salary::query()->where('actual', '>', 0)->orderByDesc('posting_date')->limit(1)->first()['posting_date']->addDays(1)->format('Y-m-d');
        }
        $students = StudentAppt::query()
            ->select('*', 
                DB::raw("'$date' AS reporting_end"), 
                DB::raw("'$start_date' AS reporting_start"), 
                DB::raw("WORKDAYS(end, start) AS days_in_appt"), 
                DB::raw("WORKDAYS(IF('$date' < end, '$date', end), IF('$start_date' > start, '$start_date', start)) AS days_in_range"), 
                DB::raw("IF(speedcode_1 = '$project->code', speedcode_1_prc, IF(speedcode_2 = '$project->code', speedcode_2_prc, speedcode_3_prc))/100 * WORKDAYS(IF('$date' < end, '$date', end), IF('$start_date' > start, '$start_date', start)) * ((IFNULL(rate_amount, 0) + IFNULL(rate_adj, 0)) / WORKDAYS(end, start)) AS remaining")
            )
            ->leftJoin('rates_view', 'student_appt.rate', '=', 'rates_view.id')
            ->where('end', '>', $start_date)
            ->where('start', '<', $date)
            ->where(function ($query) use ($project) {
                $query->where('speedcode_1', '=', $project->code)->orWhere('speedcode_2', '=', $project->code)->orWhere('speedcode_3', '=', $project->code);
            });
        return $students;
    }

    public static function student_commitment($project, $date) {
        $students = static::student_commitment_query($project, $date);
        if(!$students) {
            return 0;
        }
        $total = $students->get()->sum('remaining');
        return $total;
    }

    public static function staff_commitment_query($project, $date): ?Builder {
        $lastImport = ProjectImports::select('created_at')->orderByDesc('created_at')->limit(1)->first()['created_at'];
        if(is_null($project)) {
            return null;
        }
        $start = $project['funds_before'] ?? $project['total_award'];
        $start_date = $lastImport->format('Y-m-d');
        if(Salary::count() == 0) {
            $start_date = $lastImport->format('Y-m-d'); 
        }
        else {
            $start_date = Salary::query()->where('actual', '>', 0)->orderByDesc('posting_date')->limit(1)->first()['posting_date']->addDays(1)->format('Y-m-d');
        }
        $staff = StaffApptView::query()
            ->select('*', 
                DB::raw("'$date' AS reporting_end"), 
                DB::raw("'$start_date' AS reporting_start"), 
                DB::raw("WORKDAYS(end, start) AS days_in_appt"), 
                DB::raw("WORKDAYS(IF('$date' < end, '$date', end), IF('$start_date' > start, '$start_date', start)) AS days_in_range"), 
                DB::raw("IF(speedcode_1 = '$project->code', speedcode_1_prc, IF(speedcode_2 = '$project->code', speedcode_2_prc, speedcode_3_prc))/100 * WORKDAYS(IF('$date' < end, '$date', end), IF('$start_date' > start, '$start_date', start)) * (total_pay / WORKDAYS(end, start)) AS remaining")
            )
            ->where('end', '>', $start_date)
            ->where('start', '<', $date)
            ->where(function ($query) use ($project) {
                $query->where('speedcode_1', '=', $project->code)->orWhere('speedcode_2', '=', $project->code)->orWhere('speedcode_3', '=', $project->code);
            });
        return $staff;
    }

    public static function staff_commitment($project, $date) {
        $staff = static::staff_commitment_query($project, $date);
        if(!$staff) {
            return 0;
        }
        $total = $staff->get()->sum('remaining');
        return $total;
    }

    public static function etrac_nonsalary_commitment($project, $date) {
        $lastImport = ProjectImports::select('created_at')->orderByDesc('created_at')->limit(1)->first()['created_at'];
        if(is_null($project)) {
            return 0;
        }
        $start = $project['funds_before'] ?? $project['total_award'];
        $start_date = $lastImport->format('Y-m-d');
        $etrac = Etrac::query()
            // ->where('date', '>=', $start_date)
            // ->where('date', '<=', $date)
            ->where('project', $project->speedcode['project'])
            ->whereNot('category', 'LIKE', '%Salaries%');
        // Log::info($project);
        if($project['program']) {
            $etrac->where('program', $project['program']);
        }
        $res = $etrac->get();
        return $res->sum('commitment');
    }

    public static function funds_left($project, $date) {
        //DATE_ADD(DATE_SUB('2024-09-01', INTERVAL 6 DAY), INTERVAL (MOD(MOD(6 - DAYOFWEEK(DATE_SUB('2024-09-01', INTERVAL 6 DAY)), 7)+7,7)) DAY)
        //DATE_ADD(tmp_start, INTERVAL (MOD(MOD(6 - DAYOFWEEK(tmp_start), 7)+7,7)) DAY)
        
        //SELECT *, CEIL((WEEK(last_friday) - WEEK(first_friday))/2) AS num_periods FROM 
        // (SELECT *, DATE_ADD(tmp_start, INTERVAL (MOD(MOD(6 - DAYOFWEEK(tmp_start), 7)+7,7)) DAY) AS first_friday, 
        // DATE_ADD(DATE_SUB(tmp_end, INTERVAL 6 DAY), INTERVAL (MOD(MOD(6 - DAYOFWEEK(DATE_SUB(tmp_end, INTERVAL 6 DAY)), 7)+7,7)) DAY) AS last_friday, 
        // DATEDIFF(IF(end_diff < 0, '2025-03-31', end), IF(start_diff < 0, '2024-06-27', start)) AS appt_range FROM 
        // (SELECT id, start, end, DATEDIFF(start, '2024-06-27') AS start_diff, DATEDIFF('2025-03-31', end) AS end_diff, DATEDIFF('2025-03-31', '2024-06-27') AS range_diff, IF(DATEDIFF(start, '2024-06-27') < 0, '2024-06-27', start) AS tmp_start, IF(DATEDIFF('2024-08-31', end) < 0, '2024-08-31', end) AS tmp_end FROM student_appt WHERE start < '2025-03-31' AND end > '2024-06-27' AND 'ZACSE' IN (speedcode_1, speedcode_2, speedcode_3)) t1) t2;
        
        return static::student_commitment($project, $date) + static::staff_commitment($project, $date) + static::etrac_nonsalary_commitment($project, $date);
    }

    public function get_funds_before() {
        return $this->project_view->funds_before_calc;
        // $res = $this->funds_before;
        // if($this->speedcode->project == 'RES0042153') {
        //     $res = $this->opening_balance - Etrac::actual($this->speedcode->project, $this->program);
        // }
        // return $res;
    }

    public function get_funds_after() {
        return $this->project_view->funds_after_calc;
        // $res = $this->funds_after;
        // if($this->speedcode->project == 'RES0042153') {
        //     $res = $this->opening_balance - Etrac::actual($this->speedcode->project, $this->program) - Etrac::commitment($this->speedcode->project, $this->program);
        // }
        // return $res;
    }
}
