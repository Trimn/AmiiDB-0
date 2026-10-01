<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Terms extends Model
{
    use HasFactory, Loggable;

    protected $table = 'terms';

    protected $primaryKey = 'identifier';

    public $incrementing = false;

    protected $fillable = [
        'identifier',
        'semester',
        'year',
        'start',
        'end',
        'number'
    ];

    public static function options() {
        return static::query()->orderBy('number', 'desc')->pluck('identifier', 'identifier')->toArray();
    }

    public function rates(): HasMany {
        return $this->hasMany('rates');
    }
}
