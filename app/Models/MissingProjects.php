<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissingProjects extends Model
{
    use HasFactory;

    protected $table = 'missing_projects';

    protected $fillable = [
        'code',
    ];
}
