<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Countries extends Model
{
    use HasFactory, Loggable;

    protected $table = 'countries';

    protected $primaryKey = 'country';

    public $incrementing = false;

    protected $fillable = [
        'country'
    ];

    public static function options() {
        return static::all()->pluck('country', 'country')->toArray();
    }
}
