<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryRaw extends Model
{
    use HasFactory;

    protected $table = 'salary_raw';

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
}
