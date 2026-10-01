<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffApptRequest extends FormRequest
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
            'start' => 'required|date',
            'end' => 'required|date',
            'rate' => 'required|numeric',
            'grade' => 'nullable|numeric',
            'step' => 'nullable|numeric',
            'hours' => 'required|numeric',
            'hourly' => 'nullable',
            'speedcode_1' => 'string|required',
            'speedcode_2' => 'string|nullable',
            'speedcode_3' => 'string|nullable',
            'speedcode_1_prc' => 'numeric|required',
            'speedcode_2_prc' => 'numeric|nullable',
            'speedcode_3_prc' => 'numeric|nullable',
            'benefits' => 'numeric|required',
        ];
    }
}
