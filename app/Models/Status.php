<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory, Loggable;

    protected $table = 'status';

    protected $primaryKey = 'name';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'description'
    ];

    public static function options() {
        return static::all()->pluck('name', 'name')->toArray();
    }
}
