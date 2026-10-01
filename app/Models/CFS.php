<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CFS extends Model
{
    use HasFactory, Loggable;

    protected $table = 'cfs';

    protected $fillable = [
        'name',
        'fid',
        'speedcode',
        'po',
        'amount',
        'start',
        'end',
        'remaining',
        'status',
        'desc',
    ];

    public function fellow(): HasOne {
        return $this->hasOne(Fellows::class, 'id', 'fid');
    }
}
