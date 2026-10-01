<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Fellows;
use App\Models\Projects;
use App\Models\Speedcodes;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProjectImport implements ToModel, WithUpserts, WithValidation, WithHeadingRow, SkipsOnFailure
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    use Importable, SkipsFailures, SkipsErrors;

    public function uniqueBy() {
        return 'code';
    }

    public static function toMoney($item) {
        if(is_numeric($item)) {
            return $item;
        }
        else {
            return floatval(str_replace(['$', ',', ' '], '', $item));
        }
    }

    public function model(array $row)
    {
        $sc = Speedcodes::find($row['speed_code']);
        if($sc) {
            $sc->award_start = Carbon::parse($row['award_begin_date'])->toDateString();
            $sc->award_end = Carbon::parse($row['award_end_date'])->toDateString();
            $sc->description = $row['title'];
            $sc->save();

            return new Projects([
                'code' => $row['speed_code'],
                'total_award' => $this->toMoney($row['total_award']),
                'funds_before' => $this->toMoney($row['funds_available_before_commitments']),
                'funds_after' => $this->toMoney($row['funds_available_after_commitments']),
                'project_status' => $row['project_status'],
                'percent_spent' => $row['percent_spent'],
                'oe_status' => $row['over_expenditure_status'],
                'auth_oe_amount' => $this->toMoney($row['authorized_oe_amount']),
                'oe_auth_end' => $row['oe_authorization_end_date'] ? Carbon::parse($row['oe_authorization_end_date'])->toDateString() : null,
                'oe_req_status' => $row['oe_request_status'],
            ]);
        }
        else {
            return null;
        }
    }

    public function rules(): array {
        return [
            'speed_code' => 'required|string|size:5',
            'project_id' => 'nullable|string',
            'title' => 'nullable|string',
            'award_begin_date' => 'nullable|date',
            'award_end_date' => 'nullable|date',
            'total_award' => 'nullable',
            'funds_available_before_commitments' => 'nullable',
            'funds_available_after_commitments' => 'nullable',
            'project_status' => 'nullable',
            'percent_spent' => 'nullable',
            'oe_status' => 'nullable',
            'auth_oe_amount' => 'nullable',
            'oe_auth_end' => 'nullable|date',
            'oe_req_status' => 'nullable',
        ];
    }
}
