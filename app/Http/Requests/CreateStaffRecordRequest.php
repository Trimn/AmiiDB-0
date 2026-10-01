<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateStaffRecordRequest extends FormRequest
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
