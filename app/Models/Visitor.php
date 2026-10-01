<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kirschbaum\PowerJoins\PowerJoins;

class Visitor extends Model
{
    use HasFactory, Loggable, PowerJoins;

    protected $table = 'visitor';

    protected $fillable = [
        'pid',
        'dob',
        'ccid_requested',
        'ccid_req_date',
        'status',
        'speedcode',
        'fvca_category',
        'fvca_url',
        'letter_of_invitation',
        'airfare',
        'accomodation',
        'arrival',
        'departure',
        'on_campus',
        'workspace',
        'uofa_funding',
        'payment_amount',
        'payment_category',
        'welcomed',
        'welcome_url',
        'paf_completed',
        'paf_url',
        'notes'
    ];

    public function person(): HasOne {
        return $this->hasOne(People::class, 'id', 'pid');
    }

    public static function statuses() {
        return [
            'Active' => 'Active',
            'Inactive' => 'Inactive',
            'Coming' => 'Coming',
        ];
    }
}
