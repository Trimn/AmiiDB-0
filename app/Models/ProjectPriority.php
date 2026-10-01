<?php

namespace App\Models;

use App\Models\Loggable;
use App\Models\Projects;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectPriority extends Model
{
    use HasFactory, Loggable;

    protected $table = 'project_priority';

    protected $fillable = [
        'priority',
        'text_colour',
        'bg_colour',
    ];

    public static function options() {
        return static::all()->pluck('priority', 'id')->toArray();
    }

    public static function getBgColour($priority) {
        if(is_null($priority)) {
            return '';
        }
        $bg = $priority->bg_colour;
        return $bg ? $bg : '';
    }

    public static function getTextColour($priority) {
        if(is_null($priority)) {
            return '';
        }
        $tg = $priority->text_colour;
        return $tg ? $tg : '';
    }

    public function project(): HasMany {
        return $this->hasMany(Projects::class, 'priority_id', 'id');
    }
}
