<?php

namespace App\Models;

use App\Models\Fellows;
use App\Models\Projects;
use App\Models\Loggable;
use Kirschbaum\PowerJoins\PowerJoins;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Speedcodes extends Model
{
    use HasFactory, Loggable, PowerJoins;

    protected $table = 'speedcodes';

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'fellow',
        'description',
        'project',
        'combo_code',
        'award_start',
        'award_end',
        'status',
        'notes',
        'is_cs',
    ];

    protected $casts = [
        'is_cs' => 'boolean',
    ];

    public static function options() {
        return static::query()->where('is_cs', '=', true)->get()->pluck('code', 'code')->toArray();
    }

    public static function optionsAll() {
        return static::query()->get()->pluck('code', 'code')->toArray();
    }

    public function fellowRel(): HasOne {
        return $this->hasOne(Fellows::class, 'id', 'fellow');
    }

    public static function statusOptions() {
        return ['Active' => 'Active', 'Inactive' => 'Inactive', 'Pending' => 'Pending'];
    }

    public function projectModel(): HasOne {
        return $this->hasOne(Projects::class, 'code', 'code');
    }

    public function projectViewModel(): HasOne {
        return $this->hasOne(ProjectsView::class, 'view_code', 'code');
    }
}
