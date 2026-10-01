<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
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
            'pos_type' => 'required',
            'subtype' => 'required',
            'active' => 'required',
            'notes' => 'nullable',
            'job_title' => 'nullable',
            'dept' => 'nullable',
            'pdf_completed' => 'date|nullable',
        ];
    }
}
