<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RatesView extends Model
{
    use HasFactory, Loggable;

    protected $table = 'rates_view';

    protected $fillable = [
        'term',
        'program',
        'program_year',
        'salary_step',
        'rate_type',
        'immigration',
        'cs_award',
        'cs_salary',
        'amii_topup',
        'int_idf',
        'int_amii',
        'notes',
    ];

    public static function options() {
        // return ['CS', 'Amii', 'Top', 'Post'];
        return array('CS' => 'CS - Standard Computing Science rate',
                    'Amii' => 'Amii - Standard Amii rate',
                    'Top' => 'Top - Topup rate when the Amii student does a department TA',
                    'Post' => 'Post - Rate for PhD student who has completed their candidacy exam');
    }

    public static function list() {
        return static::all()->pluck('rate_code', 'id')->toArray();
    }

    public static function listWithSearchMetadata() {
        return static::query()
            ->orderBy('term')
            ->orderBy('program')
            ->orderBy('salary_step')
            ->orderBy('immigration')
            ->orderBy('rate_type')
            ->get(['id', 'rate_code', 'term', 'program', 'salary_step', 'immigration', 'rate_type'])
            ->map(function ($rate) {
                return [
                    'value' => (string) $rate->id,
                    'label' => $rate->rate_code,
                    'data-custom-properties' => json_encode([
                        'term' => $rate->term,
                        'program' => $rate->program,
                        'salary_step' => $rate->salary_step,
                        'immigration' => $rate->immigration,
                        'rate_type' => $rate->rate_type,
                    ]),
                ];
            })
            ->toArray();
    }
}
