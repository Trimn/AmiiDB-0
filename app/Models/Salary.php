<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory;

    protected $table = 'salary_import';

    protected $fillable = [
        'account',
        'department',
        'program',
        'project',
        'title',
        'category',
        'name',
        'uid',
        'rcd',
        'job_code',
        'appointment_date',
        'termination_date',
        'posting_date',
        'earnings_code',
        'actual',
        'commitment',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'termination_date' => 'date',
        'posting_date' => 'date',
    ];

    public static function getPersonActuals($project, $program, $id, $start, $end) {
        $query = static::query()
            ->where('project', '=', $project)
            ->whereRaw("LPAD(uid, 7, '0') COLLATE utf8mb4_general_ci = ?", [str_pad($id, 7, '0', STR_PAD_LEFT)])
            ->where('posting_date', '>=', $start)
            ->where('posting_date', '<=', $end);

        if(isset($program)) {
            $query->where('program', '=', $program);
        }

        $res = $query->get()->sum('actual');
        return $res;
    }

    public static function getProjectActuals($project, $program, $start, $end) {
        $query = static::query()
            ->where('project', '=', $project)
            ->where('posting_date', '>=', $start)
            ->where('posting_date', '<=', $end);
        if(isset($program)) {
            $query->where('program', '=', $program);
        }
        $res = $query->get()->sum('actual');
        return $res;
    }

    public static function getProjectCommitments($project, $program) {
        $start = request()->query('start', Carbon::createFromFormat('Y-m-d H:s:i', (Carbon::now()->year) . '-04-01 00:00:00')->format('Y-m-d'));
        $query = static::query()
            ->where('project',   '=', $project)
            ->where('posting_date', '>=', $start);

        if(isset($program)) {
            $query->where('program', '=', $program);
        }
        $res = $query->get()->sum('commitment');
        return $res;
    }
}
