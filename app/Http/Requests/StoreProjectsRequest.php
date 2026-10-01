<?php

namespace App\Http\Requests;

use App\Models\Projects;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectsRequest extends FormRequest
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
            'code' => 'required',
            'total_award' => 'nullable|numeric',
            'funds_before' => 'nullable|numeric',
            'funds_after' => 'nullable|numeric',
            'project_status' => 'nullable',
            'balance_alert' => ['nullable', Rule::in(array_keys(Projects::balanceAlertOptions()))],
            'percent_spent' => 'nullable|numeric',
            'oe_status' => 'nullable',
            'auth_oe_amount' => 'nullable|numeric',
            'oe_auth_end' => 'nullable|date',
            'oe_req_status' => 'nullable',
            'notes' => 'nullable',
            'who_assigned' => 'nullable',
            'priority_id' => 'nullable',
            'eval_status_id' => 'nullable',
            'financial_report' => 'nullable',
            'supervisor_review' => 'nullable',
            'future_funding' => 'nullable|numeric',
            'ff_start' => 'nullable|date',
            'ff_end' => 'nullable|date',
            'ff_verified' => 'nullable',
            'fellow' => 'required',
            'description' => 'nullable|string|between:0,30',
            'award_start' => 'nullable|date',
            'award_end' => 'nullable|date',
            'fy_start' => 'nullable|date',
            'fy_end' => 'nullable|date',
            'fy_override' => 'nullable',
            'project' => 'nullable|string|between:10,20',
            'combo_code' => 'numeric|digits:9|nullable',
            'oe_request_status' => 'nullable|string',
            'status' => 'nullable|string',
            'program' => 'nullable|numeric',
            'opening_balance' => 'nullable|numeric',
        ];
    }
}
