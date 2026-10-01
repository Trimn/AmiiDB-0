<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffType extends Model
{
    use HasFactory, Loggable;

    protected $table = 'staff_types';
    protected $fillable = [
        'name',
        'description'
    ];

    public static function options() {
        return static::all()->pluck('name', 'id')->toArray();
    }
}
