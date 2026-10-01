<?php

namespace App\Models;

use App\Models\Fellows;
use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contacts extends Model
{
    use HasFactory, Loggable;

    protected $table = 'contacts';

    protected $fillable = [
        'name',
        'position',
        'email',
        'phone',
        'department',
        'fellow_id',
        'notes',
    ];

    public function fellow(): HasOne {
        return $this->hasOne(Fellows::class, 'id', 'fellow_id');
    }

    public function fellowView(): HasOne {
        return $this->hasOne(FellowsView::class, 'id', 'fellow_id');
    }

}
