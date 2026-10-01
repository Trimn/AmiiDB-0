<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budget extends Model
{
    use HasFactory, Loggable;

    protected $table = 'budgets';

    protected $fillable = [
        'pid',
        'description',
        'start',
        'end',
        'amount',
        'code',
        'type',
        'budget_type',
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

    public static function typeOptions() {
        return [
            'PDS' => 'PDS',
            'PhD' => 'PhD',
            'MSc' => 'MSc',
            'RA' => 'RA',
            'UG' => 'UG',
            'Other' => 'Other',
        ];
    }

    public static function budgetTypeOptions() {
        // Return options with ampersand character that won't be double-encoded
        // Using the actual & character which will be properly handled
        return [
            'Salaries & Benefits' => 'Salaries & Benefits',
            'Travelling & Visitors' => 'Travelling & Visitors',
            'Compute & Equipment' => 'Compute & Equipment',
            'Supplies & Services' => 'Supplies & Services',
        ];
    }
}


