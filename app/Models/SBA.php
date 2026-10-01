<?php

namespace App\Models;

use App\Models\People;
use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SBA extends Model
{
    use HasFactory, Loggable;

    protected $table = 'sba';

    protected $fillable = [
        'date',
        'pid',
        'reason_code',
        'reason',
        'debit_speedcode',
        'credit_speedcode',
        'amount',
        'budget_holder',
        'notes',
    ];

    public function person(): HasOne {
        return $this->hasOne(People::class, 'id', 'pid');
    }
}
