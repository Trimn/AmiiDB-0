<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTeam extends Model
{
    use HasFactory;

    protected $table = 'project_team';

    protected $fillable = [
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function options(): array
    {
        $result = static::with('user')->get()->pluck('user.name', 'id')->toArray();
        $result['NULL'] = 'Unassigned';
        return $result;
    }
}
