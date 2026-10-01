<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BudgetProjectSheet implements FromArray, WithTitle, WithStyles, ShouldAutoSize
{
    private string $title;
    private array $months;
    private array $salaryPeople;
    private array $salaryMonthTotals;
    private float $salaryGrandTotal;
    private array $categories;
    private array $grandMonthTotals;
    private float $grandTotal;
    private string $fyStart;
    private string $fyEnd;
    private string $speedcode;
    private string $projectName;

    public function __construct(
        string $speedcode,
        string $projectName,
        array $reportData,
    ) {
        $this->speedcode = $speedcode;
        $this->projectName = $projectName;
        $this->months = $reportData['months'];
        $this->salaryPeople = $reportData['salaryPeople'];
        $this->salaryMonthTotals = $reportData['salaryMonthTotals'];
        $this->salaryGrandTotal = $reportData['salaryGrandTotal'];
        $this->categories = $reportData['categories'];
        $this->grandMonthTotals = $reportData['grandMonthTotals'];
        $this->grandTotal = $reportData['grandTotal'];
        $this->fyStart = $reportData['fyStart'];
        $this->fyEnd = $reportData['fyEnd'];

        // Excel sheet titles max 31 chars, no special chars
        $raw = $speedcode . ' - ' . $projectName;
        $this->title = substr(preg_replace('/[\\\\\/\?\*\[\]:]+/', '', $raw), 0, 31);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function array(): array
    {
        $monthKeys = array_keys($this->months);
        $monthNames = array_values($this->months);
        $rows = [];

        // Row 1: Project info
        $rows[] = array_merge(
            ['Project: ' . $this->speedcode . ' — ' . $this->projectName],
            array_fill(0, count($this->months) + 2, ''),
        );

        // Row 2: Date range
        $rows[] = array_merge(
            ['Period: ' . $this->fyStart . ' to ' . $this->fyEnd],
            array_fill(0, count($this->months) + 2, ''),
        );

        // Row 3: Header
        $rows[] = array_merge(['UID', 'Name', 'Type'], $monthNames, ['Total']);

        // Row 4: Salaries & Benefits subtotal
        $salaryRow = ['Salaries & Benefits', '', ''];
        foreach ($monthKeys as $m) {
            $salaryRow[] = $this->salaryMonthTotals[$m] ?? 0;
        }
        $salaryRow[] = $this->salaryGrandTotal;
        $rows[] = $salaryRow;

        // Individual salary rows
        foreach ($this->salaryPeople as $person) {
            $row = [$person['uid'], $person['name'], $person['type']];
            foreach ($monthKeys as $m) {
                $row[] = $person['months'][$m] ?? 0;
            }
            $row[] = $person['total'];
            $rows[] = $row;
        }

        if (empty($this->salaryPeople)) {
            $rows[] = array_merge(['No salary data'], array_fill(0, count($this->months) + 2, ''));
        }

        // Non-salary category rows
        foreach ($this->categories as $catName => $catData) {
            $row = [$catName, '', ''];
            foreach ($monthKeys as $m) {
                $row[] = $catData['months'][$m] ?? 0;
            }
            $row[] = $catData['total'];
            $rows[] = $row;
        }

        // Grand total row
        $totalRow = ['Total', '', ''];
        foreach ($monthKeys as $m) {
            $totalRow[] = $this->grandMonthTotals[$m] ?? 0;
        }
        $totalRow[] = $this->grandTotal;
        $rows[] = $totalRow;

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $monthCount = count($this->months);
        // Last data column letter (3 fixed cols + months + Total)
        $lastCol = chr(ord('A') + 3 + $monthCount);

        // Header row is row 3
        $headerRow = 3;

        // Salary subtotal row is row 4
        $salarySubRow = 4;

        // Grand total row is the last row
        $totalRowNum = $salarySubRow + count($this->salaryPeople) + (empty($this->salaryPeople) ? 1 : 0) + count($this->categories) + 1;

        $styles = [];

        // Project info rows bold
        $styles[1] = ['font' => ['bold' => true, 'size' => 12]];
        $styles[2] = ['font' => ['italic' => true]];

        // Header row
        $styles[$headerRow] = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '15803D']],
        ];

        // Salary subtotal row
        $styles[$salarySubRow] = [
            'font' => ['bold' => true, 'italic' => true],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DCFCE7']],
        ];

        // Grand total row
        $styles[$totalRowNum] = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '15803D']],
        ];

        // Category rows (bold with light background)
        $catStartRow = $salarySubRow + count($this->salaryPeople) + (empty($this->salaryPeople) ? 1 : 0) + 1;
        $catIndex = 0;
        foreach ($this->categories as $catName => $catData) {
            $styles[$catStartRow + $catIndex] = [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DCFCE7']],
            ];
            $catIndex++;
        }

        // Currency format for numeric columns (D onwards), starting from row 5
        $firstDataCol = 'D';
        $dataRange = $firstDataCol . $salarySubRow . ':' . $lastCol . $totalRowNum;
        $sheet->getStyle($dataRange)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_INTEGER);

        // Staff rows (italicized)
        $staffStartRow = $salarySubRow + 1;
        $staffEndRow = $salarySubRow + count($this->salaryPeople);
        if ($staffEndRow >= $staffStartRow) {
            $staffRange = 'A' . $staffStartRow . ':' . $lastCol . $staffEndRow;
            $sheet->getStyle($staffRange)->getFont()->setItalic(true);
        }

        return $styles;
    }
}
