<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supervisors extends Model
{
    use HasFactory, Loggable;

    protected $table = 'supervisors';

    protected $fillable = [
        'pid',
        'fid'
    ];
}
