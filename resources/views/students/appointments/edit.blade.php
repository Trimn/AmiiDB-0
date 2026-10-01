<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Appointment') }}
        </h2>
    </x-slot>
    
    <x-splade-modal stay>
        <div class="max-w-7xl mx-auto p-8">
            <x-splade-form :action="route('student.appt.update', $appt)" class="space-y-4" :default="$formDefaults">
                <x-splade-select name="term" label="Term" :options="$terms" :disabled="!Auth::user()->can('edit')" />
                <x-splade-input date name="start" label="Start" :readonly="!Auth::user()->can('edit')" />
                <x-splade-input date name="end" label="End" :readonly="!Auth::user()->can('edit')" />
                <x-splade-select name="appt_type" label="Appointment Type" :options="$apptTypes" :disabled="!Auth::user()->can('edit')" />
                <x-splade-input name="eform" label="Eform ID" :readonly="!Auth::user()->can('edit')" />
                <x-splade-select name="speedcode_1" label="Speedcode 1" :options="Auth::user()->can('edit') ? \App\Models\Speedcodes::options() : [$appt['speedcode_1'] => $appt['speedcode_1']]" choices :disabled="!Auth::user()->can('edit')" />
                <x-splade-input name="speedcode_1_prc" label="Speedcode 1 %" :readonly="!Auth::user()->can('edit')" />
                <x-splade-select name="speedcode_2" label="Speedcode 2" :options="Auth::user()->can('edit') ? \App\Models\Speedcodes::options() : [$appt['speedcode_2'] => $appt['speedcode_2']]" choices :disabled="!Auth::user()->can('edit')" />
                <x-splade-input name="speedcode_2_prc" label="Speedcode 2 %" :readonly="!Auth::user()->can('edit')" />
                <x-splade-select name="speedcode_3" label="Speedcode 3" :options="Auth::user()->can('edit') ? \App\Models\Speedcodes::options() : [$appt['speedcode_3'] => $appt['speedcode_3']]" choices :disabled="!Auth::user()->can('edit')" />
                <x-splade-input name="speedcode_3_prc" label="Speedcode 3 %" :readonly="!Auth::user()->can('edit')" />
                <div class="rounded-md border border-gray-200 p-4">
                    <p class="mb-3 text-sm font-semibold text-gray-700">Filter Rates</p>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
                        <x-splade-select name="rate_filter_term" label="Term" :options="$rateFilters['terms']" choices :disabled="!Auth::user()->can('edit')" />
                        <x-splade-select name="rate_filter_program" label="Program" :options="$rateFilters['programs']" choices :disabled="!Auth::user()->can('edit')" />
                        <x-splade-select name="rate_filter_salary_step" label="Salary Step" :options="$rateFilters['salarySteps']" choices :disabled="!Auth::user()->can('edit')" />
                        <x-splade-select name="rate_filter_immigration" label="Immigration" :options="$rateFilters['immigration']" choices :disabled="!Auth::user()->can('edit')" />
                        <x-splade-select name="rate_filter_rate_type" label="Rate Type" :options="$rateFilters['rateTypes']" choices :disabled="!Auth::user()->can('edit')" />
                    </div>
                </div>
                <x-splade-select
                    name="rate"
                    label="Appointment Rate"
                    remote-url="`{{ route('student.appt.rate_options') }}?term=${encodeURIComponent(form.rate_filter_term || '')}&program=${encodeURIComponent(form.rate_filter_program || '')}&salary_step=${encodeURIComponent(form.rate_filter_salary_step || '')}&immigration=${encodeURIComponent(form.rate_filter_immigration || '')}&rate_type=${encodeURIComponent(form.rate_filter_rate_type || '')}`"
                    option-value="value"
                    option-label="label"
                    :choices="[
                        'searchFields' => ['label', 'value'],
                    ]"
                    :disabled="!Auth::user()->can('edit')"
                />
                <x-splade-input name="rate_adj" label="Rate Adjustment" :readonly="!Auth::user()->can('edit')" />
                @if(Auth::user()->can('edit'))
                <x-splade-submit label="Update" />
                @endif
            </x-splade-form>
        </div>
    </x-splade-modal>
</x-app-layout>