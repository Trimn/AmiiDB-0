<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BudgetSummarySheet implements FromArray, WithTitle, WithStyles, ShouldAutoSize
{
    private int $fyYear;
    private array $sheetsData;

    public function __construct(int $fyYear, array $sheetsData)
    {
        $this->fyYear = $fyYear;
        $this->sheetsData = $sheetsData;
    }

    public function title(): string
    {
        return 'Summary';
    }

    public function array(): array
    {
        $rows = [];

        // Row 1: Title
        $rows[] = ['Budget Summary — FY ' . $this->fyYear . '-' . substr($this->fyYear + 1, 2)];

        // Collect all unique category names across all projects
        $allCategoryNames = [];
        foreach ($this->sheetsData as $data) {
            foreach (array_keys($data['reportData']['categories']) as $catName) {
                if (!in_array($catName, $allCategoryNames)) {
                    $allCategoryNames[] = $catName;
                }
            }
        }

        // Row 3: Header
        $header = ['Speed Code', 'Project Name', 'Period', 'Salaries & Benefits'];
        foreach ($allCategoryNames as $catName) {
            $header[] = $catName;
        }
        $header[] = 'Grand Total';
        $rows[] = $header;

        // One row per project
        $summaryTotalSalary = 0;
        $summaryTotalCats = array_fill(0, count($allCategoryNames), 0);
        $summaryGrandTotal = 0;

        foreach ($this->sheetsData as $data) {
            $report = $data['reportData'];
            $row = [
                $data['speedcode'],
                $data['projectName'],
                $report['fyStart'] . ' to ' . $report['fyEnd'],
                $report['salaryGrandTotal'],
            ];

            $summaryTotalSalary += $report['salaryGrandTotal'];

            foreach ($allCategoryNames as $i => $catName) {
                $catTotal = $report['categories'][$catName]['total'] ?? 0;
                $row[] = $catTotal;
                $summaryTotalCats[$i] += $catTotal;
            }

            $row[] = $report['grandTotal'];
            $summaryGrandTotal += $report['grandTotal'];

            $rows[] = $row;
        }

        // Totals row
        $totalsRow = ['Total', '', '', $summaryTotalSalary];
        foreach ($summaryTotalCats as $catTotal) {
            $totalsRow[] = $catTotal;
        }
        $totalsRow[] = $summaryGrandTotal;
        $rows[] = $totalsRow;

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $allCategoryNames = [];
        foreach ($this->sheetsData as $data) {
            foreach (array_keys($data['reportData']['categories']) as $catName) {
                if (!in_array($catName, $allCategoryNames)) {
                    $allCategoryNames[] = $catName;
                }
            }
        }

        // Column count: Speed Code, Project Name, Period, Salaries, categories..., Grand Total
        $colCount = 4 + count($allCategoryNames) + 1;
        $lastCol = chr(ord('A') + $colCount - 1);
        // Handle columns beyond Z if needed
        if ($colCount > 26) {
            $lastCol = 'A' . chr(ord('A') + $colCount - 27);
        }

        $headerRow = 2;
        $totalRow = $headerRow + count($this->sheetsData) + 1;

        // Currency columns start at D (Salaries & Benefits)
        $firstCurrencyCol = 'D';

        $styles = [];

        // Title row
        $styles[1] = ['font' => ['bold' => true, 'size' => 14]];

        // Header row
        $styles[$headerRow] = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '15803D']],
        ];

        // Totals row
        $styles[$totalRow] = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '15803D']],
        ];

        // Currency format for data rows (row after header to totals row)
        $dataStartRow = $headerRow + 1;
        $dataRange = $firstCurrencyCol . $dataStartRow . ':' . $lastCol . $totalRow;
        $sheet->getStyle($dataRange)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_INTEGER);

        return $styles;
    }
}
