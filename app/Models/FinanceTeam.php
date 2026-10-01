<?php

namespace App\Models;

use App\Models\Loggable;
use Kirschbaum\PowerJoins\PowerJoins;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinanceTeam extends Model
{
    use HasFactory, Loggable, PowerJoins;

    protected $table = 'finance_team';

    protected $fillable = [
        'name',
    ];

    public static function options() {
        // dd(static::all()->pluck('name', 'id')->toArray());
        $result = static::all()->pluck('name', 'id')->toArray();
        $result['NULL'] = 'Unassigned';
        return $result;
    }
}
