<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentApptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'term' => ['required', 'string'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
            'appt_type' => ['required'],
            'eform' => ['nullable', 'string'],
            'speedcode_1' => 'string|required',
            'speedcode_2' => 'string|nullable',
            'speedcode_3' => 'string|nullable',
            'speedcode_1_prc' => 'numeric|required',
            'speedcode_2_prc' => 'numeric|nullable',
            'speedcode_3_prc' => 'numeric|nullable',
            'rate' => 'nullable',
            'rate_adj' => 'numeric|nullable',
        ];
    }
}
