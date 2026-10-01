<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApptPayments extends Model
{
    use HasFactory, Loggable;

    protected $table = 'appt_payments';
    protected $fillable = [
        'appt_id',
        'payment_id'
    ];

    public function appt(): HasOne {
        return $this->hasOne(StudentAppt::class);
    }

    public function payment(): HasOne {
        return $this->hasOne(Payments::class);
    }
}
