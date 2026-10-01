<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NavCategories extends Model
{
    use HasFactory;

    protected $table = 'nav_categories';
    protected $primaryKey = 'category';
    public $incrementing = false;

    protected $fillable = [
        'category',
        'order',
        'colour',
    ];

    public function children(): HasMany {
        return $this->hasMany(Navigation::class, 'category', 'category')->whereIn('permission', Auth::user()->getPermissionsViaRoles()->pluck('name'))->orderBy('order');
    }

    public static function options() {
        return static::all()->pluck('category', 'category')->toArray();
    }
}
