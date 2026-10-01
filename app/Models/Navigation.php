<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Navigation extends Model
{
    use HasFactory;

    protected $table = 'navigation';
    
    protected $fillable = [
        'name',
        'route',
        'category',
        'order',
        'permission',
    ];

    public function cat(): HasOne {
        return $this->hasOne(NavCategories::class, 'category', 'category')->orderBy('order');
    }
}
