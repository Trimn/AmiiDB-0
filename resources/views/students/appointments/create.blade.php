<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Appointment') }}
        </h2>
    </x-slot>
    
    <x-splade-modal stay>
        <div class="max-w-7xl mx-auto p-8">
            <x-splade-form
                :action="route('student.appt.create', $student)"
                class="space-y-4"
                :default="$rateFilterDefaults"
            >
                <x-splade-select name="term" label="Term" :options="$terms" />
                <x-splade-input date name="start" label="Start" />
                <x-splade-input date name="end" label="End" />
                <x-splade-select name="appt_type" label="Appointment Type" :options="$apptTypes" />
                <x-splade-input name="eform" label="Eform ID" />
                <x-splade-select name="speedcode_1" label="Speedcode 1" :options="\App\Models\Speedcodes::options()" choices />
                <x-splade-input name="speedcode_1_prc" label="Speedcode 1 %" />
                <x-splade-select name="speedcode_2" label="Speedcode 2" :options="\App\Models\Speedcodes::options()" choices />
                <x-splade-input name="speedcode_2_prc" label="Speedcode 2 %" />
                <x-splade-select name="speedcode_3" label="Speedcode 3" :options="\App\Models\Speedcodes::options()" choices />
                <x-splade-input name="speedcode_3_prc" label="Speedcode 3 %" />
                <div class="rounded-md border border-gray-200 p-4">
                    <p class="mb-3 text-sm font-semibold text-gray-700">Filter Rates</p>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
                        <x-splade-select name="rate_filter_term" label="Term" :options="$rateFilters['terms']" choices />
                        <x-splade-select name="rate_filter_program" label="Program" :options="$rateFilters['programs']" choices />
                        <x-splade-select name="rate_filter_salary_step" label="Salary Step" :options="$rateFilters['salarySteps']" choices />
                        <x-splade-select name="rate_filter_immigration" label="Immigration" :options="$rateFilters['immigration']" choices />
                        <x-splade-select name="rate_filter_rate_type" label="Rate Type" :options="$rateFilters['rateTypes']" choices />
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
                />
                <x-splade-input name="rate_adj" label="Rate Adjustment" />
                @if(Auth::user()->can('edit'))
                <x-splade-submit label="Add" />
                @endif
                <x-splade-defer url="{{ route('terms.find') }}" method="POST" request="{ term: form.term }" watch-value="form.term" watch-debounce="200"
                    @success="function (response) {
                        form.start = response.start;
                        form.end = response.end;
                    }" />
            </x-splade-form>
        </div>
    </x-splade-modal>
</x-app-layout>