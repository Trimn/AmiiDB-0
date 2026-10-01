<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rates extends Model
{
    use HasFactory, Loggable;

    protected $table = 'rates';

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
}
