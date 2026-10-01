<?php

namespace App\Jobs;

use App\Models\Etrac;
use App\Models\EtracRaw;
use App\Models\EtracImport;
use Illuminate\Bus\Queueable;
use App\Imports\ProjectImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use EllGreen\LaravelLoadFile\Laravel\Facades\LoadFile;

class ProcessEtracImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $file;
    private $input;
    /**
     * Create a new job instance.
     */
    public function __construct($file, $input)
    {
        $this->file = $file;
        $this->input = $input;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $out_unix = str_replace('\\', '/', $this->file);
        LoadFile::file($out_unix, $local = true)
            ->into('etrac_raw')
            ->columns([
                'account', 
                DB::raw('@dummy'), 
                'fund', 
                'department', 
                DB::raw('@dummy'), 
                'program', 
                DB::raw('@dummy'), 
                'project', 
                DB::raw('@dummy'), 
                DB::raw('@dummy'), 
                'category', 
                DB::raw('@date'), 
                DB::raw('@actual'), 
                DB::raw('@commitment'), 
                'journal', 
                'supplier', 
                'voucher', 
                'rpt_id', 
                'invoice', 
                'invoice_no', 
                'po_no', 
                'po_line',
            ])
            ->set([
                'actual' => DB::raw('regexp_replace(@actual, \'[$, ]\', \'\')'),
                'commitment' => DB::raw('regexp_replace(@commitment, \'[$, ]\', \'\')'),
                'date' => DB::raw('STR_TO_DATE(@date, \'%m/%d/%Y\')'),
            ])
            ->fieldsTerminatedBy(',')
            ->fieldsEnclosedBy('"')
            ->ignoreLines(1)
            ->load();

        EtracRaw::query()->whereNull('project')->delete();
        EtracRaw::query()->whereNull('date')->delete();
        EtracRaw::query()->where(DB::raw('CHAR_LENGTH(account)'), '>', 6)->delete();
        EtracRaw::query()->where(DB::raw('CHAR_LENGTH(project)'), '<', 1)->delete();
        EtracRaw::query()->where('category', 'LIKE', '%Indirect Costs-BL%')->delete();
        
        $collection = EtracRaw::all();
        $dates = $collection->pluck('date');
        $min_date = $dates->min()->format('Y-m-d');
        $max_date = $dates->max()->format('Y-m-d');
        $projects = $collection->pluck('project')->unique();
        $projects->each(function ($item, $key) use ($min_date, $max_date) {
            if(!is_null($item)) {
                Etrac::where('project', $item)
                    ->where('date', '>=', $min_date)
                    ->where('date', '<=', $max_date)
                    ->delete();
            }
        });

        DB::statement('INSERT INTO etrac (account, fund, department, program, project, category, date, actual, commitment, journal, supplier, voucher, rpt_id, invoice, invoice_no, po_no, po_line) SELECT account, fund, department, program, project, category, date, actual, commitment, journal, supplier, voucher, rpt_id, invoice, invoice_no, po_no, po_line FROM etrac_raw');
        
        if(is_string($this->file)) {
            unlink($this->file);
        }
        if(is_string($this->input)) {
            unlink($this->input);
        }
        $log = new EtracImport([]);
        $log->save();
    }
}
