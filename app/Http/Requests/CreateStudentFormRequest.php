<?php

namespace App\Http\Requests;

use App\Forms\CreateStudentForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateStudentFormRequest extends FormRequest
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
        // return CreateStudentForm::rules();
        return [
            'last_name' => 'string|required',
            'first_name' => 'string|required',
            'ccid' => 'string|required',
            'email' => 'email|nullable',
            'uid' => 'numeric|required',
            'gender' => 'nullable',
            'citizenship' => 'nullable',
            'immigration' => 'nullable',
            'amii_start' => 'date|nullable',
            'amii_end' => 'date|nullable',
            'wp_type' => 'nullable',
            'wp_start' => 'date|nullable',
            'wp_end' => 'date|nullable',
            'supervisor' => 'nullable',
            'supervisor2' => 'string|nullable',
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
