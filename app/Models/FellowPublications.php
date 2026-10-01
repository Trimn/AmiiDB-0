<?php

namespace App\Models;

use Kirschbaum\PowerJoins\PowerJoins;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FellowPublications extends Model
{
    use HasFactory, Loggable, PowerJoins;

    protected $table = 'fellow_publications';

    protected $fillable = [
        'fid',
        'authors',
        'title',
        'pub_name',
        'pub_date',
        'conf_name',
        'url',
        'notes',
    ];

    public function fellowsView(): HasOne {
        return $this->hasOne(FellowsView::class, 'id', 'fid');
    }
}
