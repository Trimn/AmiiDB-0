<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateStudentRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'program' => 'required',
            'program_start' => 'required',
            'dept' => 'required',
            'active' => 'required',
            'notes' => 'nullable',
            'phd_post' => 'nullable',
            'curr_step' => 'required|numeric',
            'term_adj' => 'digits_between:0,2|nullable',
            'gf_last' => 'nullable|string',
            'convocation' => 'date|nullable',
        ];
    }
}
