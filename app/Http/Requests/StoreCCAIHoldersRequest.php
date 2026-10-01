<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreCCAIHoldersRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'last_name' => 'string|required',
            'first_name'=> 'string|required',
            'uid'=> 'numeric|required',
            'ccid'=> 'required',
            'gender' => 'nullable',
            'title' => 'string|nullable',
            'dept' => 'string|nullable',
            'email' => 'email|nullable',
            'alias'=> 'nullable',
            'notes'=> 'nullable',
        ];
    }
}
