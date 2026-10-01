<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory, Loggable;

    protected $table = 'program';

    protected $primaryKey = 'program';

    public $incrementing = false;

    protected $fillable = [
        'program',
        'description'
    ];

    public static function options() {
        return static::all()->pluck('program', 'program')->toArray();
    }
}
