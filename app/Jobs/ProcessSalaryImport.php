<?php

namespace App\Jobs;

use App\Models\Salary;
use App\Models\SalaryRaw;
use App\Models\SalaryImport;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use EllGreen\LaravelLoadFile\Laravel\Facades\LoadFile;

class ProcessSalaryImport implements ShouldQueue
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
        $tmp = LoadFile::file($out_unix, $local = true)
            ->into('salary_raw')
            ->columns([
                'account', 
                'department', 
                'program', 
                DB::raw('@dummy'), 
                'project', 
                DB::raw('@dummy'), 
                'title',
                'category', 
                'name', 
                DB::raw('@dummy'), 
                'uid',
                'rcd',
                'job_code',
                DB::raw('@appointment_date'), 
                DB::raw('@termination_date'), 
                DB::raw('@posting_date'), 
                'earnings_code',
                DB::raw('@actual'), 
                DB::raw('@commitment'), 
            ])
            ->set([
                'appointment_date' => DB::raw('STR_TO_DATE(@appointment_date, \'%m/%d/%Y\')'),
                'termination_date' => DB::raw('STR_TO_DATE(@termination_date, \'%m/%d/%Y\')'),
                'posting_date' => DB::raw('STR_TO_DATE(@posting_date, \'%m/%d/%Y\')'),
                'actual' => DB::raw('regexp_replace(@actual, \'[$, ]\', \'\')'),
                'commitment' => DB::raw('regexp_replace(@commitment, \'[$, ]\', \'\')'),
            ])
            ->fieldsTerminatedBy(',')
            ->fieldsEnclosedBy('"')
            ->linesTerminatedBy('|')
            ->ignoreLines(1)
            ->load();

        // SalaryRaw::query()->whereNull('project')->delete();
        // SalaryRaw::query()->where(DB::raw('CHAR_LENGTH(project)'), '<', 1)->delete();

        $collection = SalaryRaw::all();
        $dates = $collection->pluck('posting_date');
        $min_date = $dates->min();
        $max_date = $dates->max();
        $projects = $collection->pluck('project')->unique();
        $projects->each(function ($item, $key) use ($min_date, $max_date) {
            if(!is_null($item)) {
                Salary::where('project', $item)
                    ->where('posting_date', '>=', $min_date)
                    ->where('posting_date', '<=', $max_date)
                    ->delete();
            }
        });

        DB::statement('INSERT INTO salary_import (account, department, program, project, title, category, name, uid, rcd, job_code, appointment_date, termination_date, posting_date, earnings_code, actual, commitment) SELECT account, department, program, project, title, category, name, uid, rcd, job_code, appointment_date, termination_date, posting_date, earnings_code, actual, commitment FROM salary_raw');

        // $collection->each(function ($item, $key) {
        //     if(isset($item['project'])) {
        //         // $item['actual'] = ProjectImport::toMoney($item['actual_amount']);
        //         // $item['commitment'] = ProjectImport::toMoney($item['commitment_amount']);
        //         Salary::create($item->toArray());
        //         return $item;
        //     }
        // });
        unlink($this->file);
        unlink($this->input);
        $log = new SalaryImport([]);
        $log->save();
    }
}
