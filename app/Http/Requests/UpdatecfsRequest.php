<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdatecfsRequest extends FormRequest
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
            'name' => 'required',
            'fid' => 'required',
            'speedcode' => 'required',
            'po' => 'required|digits:6',
            'amount' => 'required|numeric',
            'start' => 'required',
            'end' => 'required',
            'remaining' => 'required|numeric',
            'status' => 'required',
            'desc' => 'nullable',
        ];
    }
}
