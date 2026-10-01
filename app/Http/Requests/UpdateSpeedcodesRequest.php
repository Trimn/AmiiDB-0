<?php

namespace App\Http\Requests;

use App\Models\Speedcodes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSpeedcodesRequest extends FormRequest
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
            'code' => 'size:5',
            'description' => 'nullable|between:0,30',
            'project' => 'between:10,20|nullable',
            'combo_code' => 'digits:9|nullable',
            'fellow' => 'required',
            'notes' => 'nullable',
            'award_start' => 'date|nullable',
            'award_end' => 'date|nullable',
            'is_cs' => 'boolean|required',
            'status' => [
                'required',
                Rule::in(Speedcodes::statusOptions()),
            ]
        ];
    }
}
