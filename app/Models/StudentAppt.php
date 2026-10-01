<?php

namespace App\Models;

use App\Models\Loggable;
use App\Models\Speedcodes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kirschbaum\PowerJoins\PowerJoins;

class StudentAppt extends Model
{
    use HasFactory, Loggable, PowerJoins;

    protected $table = 'student_appt';

    protected $fillable = [
        'sid',
        'term',
        'start',
        'end',
        'appt_type',
        'eform',
        'speedcode_1',
        'speedcode_1_prc',
        'speedcode_2',
        'speedcode_2_prc',
        'speedcode_3',
        'speedcode_3_prc',
        'rate',
        'rate_adj'
    ];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class, 'sid');
    }

    public function rate_view(): HasOne {
        return $this->hasOne(RatesView::class, 'id', 'rate');
    }

    public function appt(): HasOne {
        return $this->hasOne(ApptType::class, 'id', 'appt_type');
    }

    public function speedcode1(): HasOne {
        return $this->hasOne(Speedcodes::class, 'code', 'speedcode_1');
    }

    public function speedcode2(): HasOne {
        return $this->hasOne(Speedcodes::class, 'code', 'speedcode_2');
    }

    public function speedcode3(): HasOne {
        return $this->hasOne(Speedcodes::class, 'code', 'speedcode_3');
    }
}
