<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\SalaryRaw;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SalaryImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue, ShouldBeUnique
{
    use Importable, SkipsErrors, SkipsFailures;

    public function model(array $row)
    {
        // dd($row);
        return new SalaryRaw([
            'account' => $row['account'],
            'department' => $row['department'],
            'program' => $row['program'],
            'project' => $row['project'],
            'title' => $row['title'],
            'category' => $row['category'],
            'name' => $row['name'],
            'uid' => $row['empl_id'],
            'rcd' => $row['rcd'],
            'job_code' => $row['job_code'],
            'appointment_date' => Carbon::parse($row['appointment_date'])->format('Y-m-d'),
            'termination_date' => Carbon::parse($row['termination_date'])->format('Y-m-d'),
            'posting_date' => Carbon::parse($row['posting_date'])->format('Y-m-d'),
            'earnings_code' => $row['earnings_code'],
            'actual' => ProjectImport::toMoney($row['actual_amount']),
            'commitment' => ProjectImport::toMoney($row['commitment_amount']),
        ]);
    }

    public function chunkSize(): int {
        return 50;
    }
}
