<?php

namespace App\Models;

use App\Models\People;
use Illuminate\Support\Facades\DB;
use Kirschbaum\PowerJoins\PowerJoins;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Affiliates extends Model
{
    use HasFactory, Loggable, PowerJoins;

    protected $table = 'affiliates';
    protected $fillable = [
        'pid',
        'title',
        'affiliation',
        'alternate_email',
        'website',
        'notes',
    ];

    public function person(): HasOne {
        return $this->hasOne(People::class, 'id', 'pid');
    }

    public function name() {
        return $this->person->first_name . ' ' . $this->person->last_name;
    }

    public static function options() {
        return static::query()
            ->join('people', 'affiliates.pid', '=', 'people.id')
            ->select('affiliates.id as id', DB::raw('CONCAT(`first_name`, " ", `last_name`) as name'))
            ->pluck('name', 'id')
            ->toArray();
    }

}
