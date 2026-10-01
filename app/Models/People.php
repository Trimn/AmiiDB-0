<?php

namespace App\Models;

use App\Models\Fellows;
use App\Models\Loggable;
use Illuminate\Support\Facades\DB;
use Kirschbaum\PowerJoins\PowerJoins;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class People extends Model
{
    use HasFactory, Loggable, PowerJoins;

    protected $table = 'people';

    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'uid',
        'ccid',
        'gender',
        'citizenship',
        'immigration',
        'amii_start',
        'amii_end',
        'wp_type',
        'wp_start',
        'wp_end',
        'supervisor',
        'supervisor2',
        'post_uofa_employer',
        'post_uofa_employer_updated_at'
    ];

    public function gender(): HasOne {
        return $this->hasOne(Gender::class);
    }

    public function citizenship(): HasOne {
        return $this->hasOne(Countries::class);
    }

    public function immigration(): HasOne {
        return $this->hasOne(Immigration::class);
    }

    public function _supervisor(): HasOne {
        return $this->hasOne(Fellows::class, 'id', 'supervisor');
    }

    public function supervisor_view(): HasOne {
        return $this->hasOne(FellowsView::class, 'id', 'supervisor');
    }

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class, 'id', 'pid');
    }

    public function affiliate(): BelongsTo {
        return $this->belongsTo(Affiliates::class, 'id', 'pid');
    }

    public function staff(): BelongsTo {
        return $this->belongsTo(Staff::class, 'id', 'pid');
    }

    public function fellow(): BelongsTo {
        return $this->belongsTo(Fellows::class, 'id', 'pid');
    }

    public static function wp_types() {
        return ['study' => 'Study', 'work' => 'Work'];
    }

    public static function listNonFellows() {
        return static::query()
            ->select('id', DB::raw('CONCAT(`first_name`, " ", `last_name`) as name'))
            ->whereNotIn('id', fn ($query) => $query->select('pid')->from('fellows'))
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }

    public static function listAll() {
        return static::query()
            ->select('id', DB::raw('CONCAT(`first_name`, " ", `last_name`) as name'))
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }

    public static function supervisor2() {
        return static::query()->select('supervisor2')->distinct()->get()->pluck('supervisor2', 'supervisor2')->toArray();
    }


    // public function supervisors(): BelongsToMany {
    //     return $this->belongsToMany(Fellows::class, 'supervisors', 'pid', 'fid');
    // }
}
