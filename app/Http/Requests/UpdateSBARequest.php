<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSBARequest extends FormRequest
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
            'date' => 'date|required',
            'pid' => 'numeric|required',
            'reason_code' => 'digits:3|required',
            'reason' => 'required',
            'debit_speedcode' => 'string|required',
            'credit_speedcode' => 'string|required',
            'amount' => 'numeric|required',
            'budget_holder' => 'string|required',
            'notes' => 'nullable',
        ];
    }
}
