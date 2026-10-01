<?php

namespace App\Imports;

use Log;
use Exception;
use Carbon\Carbon;
use App\Models\Etrac;
use App\Models\EtracRaw;
use App\Imports\ProjectImport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class EtracImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue, ShouldBeUnique
{
    use Importable, SkipsErrors, SkipsFailures;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        print_r($collection);
        die();
    }

    public function model(array $row)
    {   
        // if($row['project'] == 'RES0065808') {
        //     \Log::info($row);
        //     print_r($row);
        //     dd($row);
        // }
        if(!isset($row['project'])) {
            return null;
        }
        try {
            $date = Carbon::parse($row['date']);
        }
        catch(Exception $e) {
            return null;
        }
        if($row['category'].contains('Indirect Costs-BL')) {
            return null;
        }
        // Log::info($row);
        return new EtracRaw([
            'account' => $row['account'],
            'fund' => $row['fund'],
            'department' => $row['department'],
            'project' => $row['project'],
            'program' => $row['program'],
            'category' => $row['category'],
            'date' => $row['date'],
            'actual' => ProjectImport::toMoney($row['actual_amount']),
            'commitment' => ProjectImport::toMoney($row['commitment_amount']),
            'journal' => $row['journal'] ?? null,
            'supplier' => $row['supplier'] ?? null,
            'voucher' => $row['voucher'] ?? null,
            'rpt_id' => $row['rpt_id'] ?? null,
            'invoice' => $row['invoice'] ?? null,
            'invoice_no' => $row['invoice_no'] ?? null,
            'po_no' => $row['po_no'] ?? null,
            'po_line' => $row['po_line_number'] ?? $row['po_line'] ?? null,
        ]);
    }

    public function chunkSize(): int {
        return 50;
    }

    public function batchSize(): int {
        return 50;
    }
}
