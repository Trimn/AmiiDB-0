<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Loggable;
use Illuminate\Support\Facades\DB;
use Kirschbaum\PowerJoins\PowerJoins;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fellows extends Model
{
    use HasFactory, Loggable, PowerJoins;

    protected $table = 'fellows';

    protected $fillable = [
        'pid',
        'supid',
        'report_id',
        'assistant_name',
        'assistant_email',
        'start',
        'notes',
        'title',
        'photo',
        'website',
        'committees',
        'dept',
        'office',
        'phone',
        'alias',
        'sal_chair',
        'pub_platform_primary',
        'pub_list_location',
        'temporary_id',
        'ccai_chair',
        'ccai_start',
        'ccai_end',
    ];

    protected $casts = [
        'start' => 'date',
        'ccai_start' => 'date',
        'ccai_end' => 'date',
        'temporary_id' => 'boolean',
        'ccai_chair' => 'boolean',
    ];

    public static function options() {
        return static::query()
            ->join('people', 'fellows.pid', '=', 'people.id')
            ->select('fellows.id as id', DB::raw('CONCAT(`first_name`, " ", `last_name`) as name'))
            ->where('sal_chair', 0)
            ->orWhereNull('sal_chair')
            ->orderBy('last_name')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }

    public static function optionsAll() {
        return static::query()
            ->join('people', 'fellows.pid', '=', 'people.id')
            ->select('fellows.id as id', DB::raw('CONCAT(`first_name`, " ", `last_name`) as name'))
            ->orderBy('last_name')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }

    public static function salOptions() {
        return static::query()
            ->join('people', 'fellows.pid', '=', 'people.id')
            ->select('fellows.id as id', DB::raw('CONCAT(`first_name`, " ", `last_name`) as name'))
            ->where('sal_chair', 1)
            ->orderBy('last_name')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }


    public static function optionsAliases() {
        return static::query()->get()->pluck('alias')->merge(collect(static::options())->values())->toArray();
    }

    public function person(): HasOne {
        return $this->hasOne(People::class, 'id', 'pid');
    }

    public function students(): BelongsToMany {
        return $this->belongsToMany(Student::class, 'people', 'supervisor', 'id', null, 'pid');
    }

    public function staff(): BelongsToMany {
        return $this->belongsToMany(Staff::class, 'people', 'supervisor', 'id', null, 'pid');
    }

    public function name() {
        return $this->person->first_name . ' ' . $this->person->last_name;
    }

    public static function findFellow(User $user) {
        $email = $user->email;
        $username = explode('@', $email)[0];
        return static::whereRelation('person', 'ccid', '=', $username)->first();
    }
}
