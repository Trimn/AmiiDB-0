<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transactions extends Model
{
    use HasFactory, Loggable;

    protected $table = 'transactions';

    protected $fillable = [
        'first_name',
        'last_name',
        'tr_date',
        'amount',
        'speedcode'
    ];
}
