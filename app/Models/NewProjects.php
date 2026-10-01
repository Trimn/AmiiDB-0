<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewProjects extends Model
{
    use HasFactory;

    protected $table = 'new_projects';
    
    protected $fillable = [
        'holder',
        'project_id',
        'award_start',
        'award_end',
        'code',
        'title',
        'total_award',
        'funds_before',
        'funds_after',
        'project_status',
        'percent_spent',
        'oe_status',
        'auth_oe_amount',
        'oe_auth_end',
        'oe_req_status',
    ];
}
