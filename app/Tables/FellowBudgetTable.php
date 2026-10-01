<?php

namespace App\Tables;

use Illuminate\Http\Request;
use Illuminate\Support\Number;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class FellowBudgetTable extends AbstractTable
{
    private $speedcodes;
    private $fyYear;

    public function __construct($speedcodes, $fyYear)
    {
        $this->speedcodes = $speedcodes;
        $this->fyYear = $fyYear;
    }

    public function authorize(Request $request)
    {
        return true;
    }

    public function for()
    {
        return $this->speedcodes;
    }

    public function configure(SpladeTable $table)
    {
        $table
            ->name('fellow_budget')
            ->column('project', 'Project')
            ->column('description', 'Name')
            ->column('code', 'Speed Code')
            ->column('award_start', 'Start Date', as: fn($item) => $item ?? '—')
            ->column('award_end', 'End Date', as: fn($item) => $item ?? '—')
            ->column('fy_start_date', 'FY Start', as: fn($item) => $item ?? '—')
            ->column('fy_end_date', 'FY End', as: fn($item) => $item ?? '—')
            ->column('open_balance_fy_start', 'Open Bal. as of FY Start', alignment: 'right', as: fn($item) => Number::currency($item ?? 0))
            ->column('projectModel.future_funding', 'Future Funding', alignment: 'right', as: fn($item) => $item ? Number::currency($item) : '—')
            ->column('act_ytd', 'ACT YTD', alignment: 'right', as: fn($item) => $item ? Number::currency($item) : '—')
            ->column('commitments', 'Commitments', alignment: 'right', as: fn($item) => $item ? Number::currency($item) : '—')
            ->column('funds_after', 'Funds available AFTER commitments', alignment: 'right', as: fn($item) => Number::currency($item ?? 0));
    }
}
