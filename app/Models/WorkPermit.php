<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkPermit extends Model
{
    use HasFactory, Loggable;

    protected $table = 'work_permit';
    protected $fillable = [
        'sid',
        'start',
        'end'
    ];

    public function sid(): HasOne {
        return $this->hasOne('staff');
    }
}
