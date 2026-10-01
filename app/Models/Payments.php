<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payments extends Model
{
    use HasFactory, Loggable;

    protected $table = 'payments';

    protected $fillable = [
        'pid',
        'name',
        'start',
        'end',
        'code',
        'amount',
        'notes',
    ];

    protected $casts = [
        'start' => 'date',
        'end' => 'date',
    ];

    public function person(): HasOne {
        return $this->hasOne(People::class, 'id', 'pid');
    }

    public function speedcode(): HasOne {
        return $this->hasOne(Speedcodes::class, 'code', 'code');
    }
}
