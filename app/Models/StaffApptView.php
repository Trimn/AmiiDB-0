<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffApptView extends Model
{
    use HasFactory, Loggable;

    protected $table = 'staff_appt_view';

    protected $fillable = [
        'staff_id',
        'start',
        'end',
        'rate',
        'grade',
        'step',
        'hours',
        'hourly',
        'speedcode_1',
        'speedcode_1_prc',
        'speedcode_2',
        'speedcode_2_prc',
        'speedcode_3',
        'speedcode_3_prc',
        'benefits',
    ];

    public function staff(): BelongsTo {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
