<?php

namespace App\Models;

use App\Models\Loggable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AffiliateStaff extends Model
{
    use HasFactory, Loggable;

    protected $table = 'affiliate_staff';

    protected $fillable = [
        'first_name',
        'last_name',
        'sup_id',
        'supervisor2',
        'type',
        'start',
        'end',
        'notes',
    ];

    public static function types() {
        return ['MSc' => 'MSc', 'PhD' => 'PhD', 'Undergrad Student' => 'Undergrad Student', 'PDS' => 'PDS', 'Other' => 'Other'];
    }

    public function supervisor(): HasOne {
        return $this->hasOne(People::class, 'id', 'sup_id');
    }

    public function supervisor_view(): HasOne {
        return $this->hasOne(PeopleView::class, 'id', 'sup_id');
    }

    public static function supervisors() {
        return People::has('affiliate')
                ->select('id', DB::raw('CONCAT(first_name, " ", last_name) AS name'))
                ->union(
                    People::whereHas('fellow', function (Builder $query) {
                        $query->whereNot('dept', 'Computing Science');
                    })
                    ->select('id', DB::raw('CONCAT(first_name, " ", last_name) AS name'))
                )
                ->pluck('name', 'id')
                ->toArray();
    }
}
