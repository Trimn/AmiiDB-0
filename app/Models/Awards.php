<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Awards extends Model
{
    use HasFactory, Loggable;

    protected $table = 'awards';

    protected $fillable = [
        'pid',
        'amount',
        'start',
        'end',
        'name',
        'award_notification',
    ];

    /**
     * Get the pid associated with the Awards
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function person(): HasOne {
        return $this->hasOne(People::class, 'id', 'pid');
    }

}
