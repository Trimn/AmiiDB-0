<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Immigration extends Model
{
    use HasFactory, Loggable;

    protected $table = 'immigration';

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'description'
    ];

    public static function options() {
        return static::all()->pluck('code', 'code')->toArray();
    }
}
