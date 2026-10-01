<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EtracImport extends Model
{
    use HasFactory, Loggable;

    protected $table = 'etrac_import';

    public static function lastUploadDate() {
        $last_date = static::select('created_at')->orderByDesc('created_at')->limit(1)->first();
        if ($last_date) {
            return "Last upload: " . Carbon::createFromFormat('Y-m-d H:i:s', $last_date->created_at, 'UTC')->setTimezone('America/Edmonton');
        }
        return "";
    }
}
