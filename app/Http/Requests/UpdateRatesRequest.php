<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRatesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'term' => 'required',
            'program' => 'required',
            'program_year' => 'required|integer|gt:0|lt:100',
            'salary_step' => 'required|integer|gt:0|lt:100',
            'rate_type' => 'required',
            'immigration' => 'required',
            'cs_award' => 'required|numeric|gt:0',
            'cs_salary' => 'required|numeric|gt:0',
            'amii_topup' => 'required|numeric|gt:0',
            'int_idf' => 'required|numeric|gt:0',
            'int_amii' => 'required|numeric|gt:0',
            'notes' => 'nullable'
        ];
    }
}
