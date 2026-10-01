<?php

namespace App\Http\Requests;

use App\Models\Projects;
use App\Imports\ProjectImport;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }


    protected function prepareForValidation(): void {
        $this->merge([
            'total_award' => ProjectImport::toMoney($this->total_award),
            'funds_before' => ProjectImport::toMoney($this->funds_before),
            'funds_after' => ProjectImport::toMoney($this->funds_after),
            'auth_oe_amount' => ProjectImport::toMoney($this->auth_oe_amount),
            'future_funding' => ProjectImport::toMoney($this->future_funding),
            'percent_spent' => floatval(str_replace(['$', ',', ' ', '%'], '', $this->percent_spent)),
        ]);
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
            'future_funding' => 'nullable|numeric',
            'ff_start' => 'nullable|date',
            'ff_end' => 'nullable|date',
            'ff_verified' => 'nullable',
            'notes' => 'nullable',
            'who_assigned' => 'nullable',
            'priority_id' => 'nullable',
            'eval_status_id' => 'nullable',
            'financial_report' => 'nullable',
            'supervisor_review' => 'nullable',
            'fellow' => 'required',
            'description' => 'nullable|string|between:0,30',
            'award_start' => 'nullable|date',
            'award_end' => 'nullable|date',
            'fy_start' => 'nullable|date',
            'fy_end' => 'nullable|date',
            'fy_override' => 'nullable',
            'project' => 'nullable|string|between:10,20',
            'combo_code' => 'numeric|digits:9|nullable',
            'total_award' => 'numeric|nullable',
            'funds_before' => 'numeric|nullable',
            'funds_after' => 'numeric|nullable',
            'project_status' => 'string',
            'balance_alert' => ['nullable', Rule::in(array_keys(Projects::balanceAlertOptions()))],
            'percent_spent' => 'numeric|nullable',
            'oe_status' => 'nullable|string',
            'auth_oe_amount' => 'numeric|nullable',
            'oe_auth_end' => 'date|nullable',
            'oe_req_status' => 'nullable|string',
            'status' => 'nullable|string',
            'program' => 'nullable|numeric',
            'opening_balance' => 'nullable|numeric',
        ];
    }
}
