<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffSubtype extends Model
{
    use HasFactory, Loggable;

    protected $table = 'staff_subtypes';

    protected $fillable = [
        'name',
        'description'
    ];

    public static function options() {
        return static::all()->pluck('name',  'id')->toArray();
    }
}
