<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectsView extends Model
{
    use HasFactory;

    protected $table = "projects_view";

    protected $fillable = [
        'proj_id',
        'view_code',
        'funds_before_calc',
        'funds_after_calc',
        'curr_fy_start',
        'curr_fy_end',
    ];

    protected $casts = [
        'funds_before_calc' => 'double',
        'funds_after_calc' => 'double',
    ];
}
