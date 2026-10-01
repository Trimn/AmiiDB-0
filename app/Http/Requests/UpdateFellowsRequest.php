<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFellowsRequest extends FormRequest
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
            'gender' => 'nullable',
            'ccid' => 'string|required',
            'title' => 'string|nullable',
            'dept' => 'string|nullable',
            'email' => 'email|nullable',
            'uid' => 'numeric|required',
            'report_id' => 'nullable',
            'assistant_name' => 'string|nullable',
            'assistant_email' => 'email|nullable',
            'start' => 'date|nullable',
            'notes' => 'nullable',
            'photo' => 'nullable',
            'fellow_start' => 'nullable',
            'website' => 'string|nullable',
            'committees' => 'string|nullable',
            'office' => 'string|nullable',
            'phone' => 'string|nullable',
            'alias' => 'string|nullable',
            'pub_platform_primary' => 'string|nullable',
            'pub_list_location' => 'string|nullable',
            'temporary_id' => 'boolean',
            'ccai_chair' => 'boolean|nullable',
            'ccai_start' => 'date|nullable',
            'ccai_end' => 'date|nullable',
        ];
    }
}
