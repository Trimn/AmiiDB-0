<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApptType extends Model
{
    use HasFactory, Loggable;

    protected $table = 'appt_type';
    protected $fillable = [
        'name',
        'description'
    ];

    public static function options() {
        return static::all()->pluck('name', 'id')->toArray();
    }
}
