<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Etrac extends Model
{
    use HasFactory;

    protected $table = 'etrac';

    protected $fillable = [
        'account',
        'fund',
        'department',
        'program',
        'project',
        'category',
        'date',
        'actual',
        'commitment',
        'journal',
        'supplier',
        'voucher',
        'rpt_id',
        'invoice',
        'invoice_no',
        'po_no',
        'po_line',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public static function actual($proj, $prog, $start = null, $end = null) {
        // $fy = request()->query('fy', Carbon::now()->year);
        if($proj == 'RES0042153') {
            $project = Projects::where('code', '=', '36BOW')->get()->first();
        }
        else {
            $project = Speedcodes::where('project', '=', $proj)->get()->first()->projectModel;
        }
        $act = static::query()
            ->select(DB::raw('SUM(actual) as act_sum'))
            ->where('project', '=', $proj);
        
        // Use provided start date or fall back to fiscal year start
        if ($start) {
            $act->where('date', '>=', $start);
        } else {
            $act->where('date', '>=', $project->project_view->curr_fy_start);
        }
        
        // Use provided end date if available
        if ($end) {
            $act->where('date', '<=', $end);
        }
        
        if(isset($prog)) {
            $act->where('program', '=', $prog);
        }
        $res = $act->get()->first();
        // dd($res);
        return $res['act_sum'] ?? 0;
    }

    public static function commitment($proj, $prog) {
        if($proj == 'RES0042153') {
            return Salary::getProjectCommitments($proj, $prog);
        }
        $act = static::query()->select(DB::raw('SUM(commitment) as comm_sum'))->where('project', '=', $proj);
        if(isset($prog)) {
            $act->where('program', '=', $prog);
        }
        $res = $act->get()->first();
        // dd($res);
        return $res['comm_sum'] ?? 0;
    }

    public static function computed_funds($proj, $prog, $start = null, $end = null) {
        $act = Etrac::actual($proj, $prog, $start, $end);
        $com = Etrac::commitment($proj, $prog);
        return $act + $com;
    }

    public static function getFiscalYears() {
        return static::query()->select(DB::raw('YEAR(date) as fy'))->distinct()->orderBy('fy')->get()->pluck('fy', 'fy')->toArray();
    }
}
