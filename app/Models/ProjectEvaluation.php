<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectEvaluation extends Model
{
    use HasFactory, Loggable;

    protected $table = 'project_evaluation';

    protected $fillable = [
        'status',
        'text_colour',
        'bg_colour',
    ];

    public static function options() {
        return static::all()->pluck('status', 'id')->toArray();
    }

    public static function getBgColour($eval_status) {
        if(is_null($eval_status)) {
            return '';
        }
        $bg = $eval_status->bg_colour;
        return $bg ? $bg : '';
    }

    public static function getTextColour($eval_status) {
        if(is_null($eval_status)) {
            return '';
        }
        $tg = $eval_status->text_colour;
        return $tg ? $tg : '';
    }
}
