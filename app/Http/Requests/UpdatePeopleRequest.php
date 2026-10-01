<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePeopleRequest extends FormRequest
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
            'post_uofa_employer' => 'string|nullable',
        ];
    }
}
