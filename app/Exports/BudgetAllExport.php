<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BudgetAllExport implements WithMultipleSheets
{
    private array $sheets;

    /**
     * @param  int    $fyYear      The fiscal year for the summary title
     * @param  array  $sheetsData  Array of ['speedcode' => string, 'projectName' => string, 'reportData' => array]
     */
    public function __construct(int $fyYear, array $sheetsData)
    {
        // First sheet: summary of all accounts
        $this->sheets = [new BudgetSummarySheet($fyYear, $sheetsData)];

        // One sheet per project
        foreach ($sheetsData as $data) {
            $this->sheets[] = new BudgetProjectSheet(
                $data['speedcode'],
                $data['projectName'],
                $data['reportData'],
            );
        }
    }

    public function sheets(): array
    {
        return $this->sheets;
    }
}
