<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            'program' => 'required',
            'phd_post' => 'nullable',
            'program_start' => 'required',
            'dept' => 'required',
            'curr_step' => 'numeric|required',
            'term_adj' => 'digits_between:0,2|nullable',
            'gf_last' => 'nullable',
            'convocation' => 'date|nullable',
            'active' => 'required',
            'notes' => 'nullable',
        ];
    }
}
