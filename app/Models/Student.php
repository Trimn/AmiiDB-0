<?php

namespace App\Models;

use App\Models\Terms;
use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Style\Supervisor;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Kirschbaum\PowerJoins\PowerJoins;

class Student extends Model
{
    use HasFactory, Loggable, PowerJoins;

    protected $table = 'student';

    protected $fillable = [
        'pid',
        'program',
        'program_start',
        'dept',
        'phd_post',
        'phd_post_checked',
        'curr_step',
        'term_adj',
        'gf_last',
        'notes',
        'active',
        'convocation'
    ];

    protected $appends = [
        'is_phd'
    ];

    public function getIsPhdAttribute() {
        return $this->program == 'PhD';
    }

    public function person(): HasOne {
        return $this->hasOne(People::class, 'id', 'pid');
    }

    public function program(): HasOne {
        return $this->hasOne(Program::class);
    }

    public function active(): HasOne {
        return $this->hasOne(Status::class);
    }

    public function appt(): HasMany {
        return $this->hasMany(StudentAppt::class, 'sid', 'id');
    }

    public function gf_last_term(): HasOne {
        return $this->hasOne(Terms::class, 'identifier', 'gf_last');
    }

}
