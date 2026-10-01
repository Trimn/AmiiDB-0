<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtracRaw extends Model
{
    use HasFactory;

    protected $table = 'etrac_raw';

    protected $fillable = [
        'account',
        'fund',
        'department',
        'program',
        'project',
        'category',
        'date',
        'actual',
        'commitment',
        'journal',
        'supplier',
        'voucher',
        'rpt_id',
        'invoice',
        'invoice_no',
        'po_no',
        'po_line',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
