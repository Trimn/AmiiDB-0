<?php

namespace App\Models;

use App\Models\Loggable;
use App\Models\StaffType;
use App\Models\StaffSubtype;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kirschbaum\PowerJoins\PowerJoins;

class Staff extends Model
{
    use HasFactory, Loggable, PowerJoins;

    protected $table = 'staff';

    protected $fillable = [
        'pid',
        'notes',
        'pos_type',
        'subtype',
        'active',
        'job_title',
        'dept',
        'pdf_completed',
    ];

    public function person(): HasOne {
        return $this->hasOne(People::class, 'id', 'pid');
    }

    public function position(): HasOne {
        return $this->hasOne(StaffType::class, 'id', 'pos_type');
    }

    public function pos_subtype(): HasOne {
        return $this->hasOne(StaffSubtype::class, 'id', 'subtype');
    }

    public function appt(): HasMany {
        return $this->hasMany(StaffAppt::class, 'staff_id', 'id');
    }
}
