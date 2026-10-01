<?php

namespace App\Models;

use App\Models\People;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactPreferences extends Model
{
    use HasFactory, Loggable;

    protected $table = 'contact_preferences';

    protected $fillable = [
        'pid',
        'alternate_email',
        'contact_method',
        'meeting_email',
        'calendar_url',
        'work_schedule',
        'doc_pref',
        'signature_url',
        'assistant_name',
        'assistant_email',
        'notes',
    ];

    public function person(): HasOne {
        return $this->hasOne(People::class, 'id', 'pid');
    }
}
