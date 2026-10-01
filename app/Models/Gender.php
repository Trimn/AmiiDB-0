<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gender extends Model
{
    use HasFactory, Loggable;

    protected $table = 'gender';

    protected $primaryKey = 'gender';

    public $incrementing = false;

    protected $fillable = [
        'gender'
    ];

    public static function options() {
        return static::all()->pluck('gender', 'gender')->toArray();
    }
}
