@seoTitle('Forecasting')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Forecasting') }}
        </h2>
    </x-slot>
    <div class="max-w-fit mx-auto p-8">
        <x-splade-toggle>
            <div class="max-w-fit mx-8 my-4 bg-amber-300 border border-orange-300 rounded-md" v-if="!toggled">
                <button class="float-right px-2" @click.prevent="toggle">&times;</button>
                <div class="text-sm p-4">
                    <p class="py-2">
                        This report gives the forecasted expenditures for a given speed-code.  A start date may be selected to indicate which appointments to include in the report.  However, the start date for forecasting purposes begins one day after the last pay period found in the most recent eTRAC upload.  This date may differ from person to person due to pay periods for certain employment types.  The forecast will be calculated to the end of the appointment or the forecasting end date that is entered, whichever is earlier.
                    </p>
                </div>
            </div>
        </x-splade-toggle>
        <x-splade-form :default="['start' => $start, 'end' => $end]" method="GET" action="{{ route('forecasting') }}" submit-on-change>
            <div class="w-1/2 mx-auto py-2 grid grid-cols-2 gap-4">
                <x-splade-input name="start" label="Start" date />
                <x-splade-input name="end" label="End" date />
            </div>
        </x-splade-form>
        <x-splade-rehydrate on="projects-updated">
            <x-splade-table :for="$table" pagination-scroll="preserve" striped>
                <x-splade-cell days-left>
                    @php $val = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($item->speedcode['award_end']), false) @endphp
                    @if($val < 0)
                        <div class="bg-red-400 text-black px-2 py-1 rounded-md">
                            {{ $val }}
                        </div>
                    @else
                        {{ $val }}
                    @endif
                </x-splade-cell>
                <x-splade-cell project_view.funds_before_calc>
                    @php $val = $item->get_funds_before() @endphp
                    @if($val < 0)
                        <div class="bg-red-400 text-black px-2 py-1 rounded-md">
                            {{ is_null($val) ? $val : Number::currency($val) }}
                        </div>
                    @else
                        {{ is_null($val) ? $val : Number::currency($val) }}
                    @endif
                </x-splade-cell>
                <x-splade-cell project_view.funds_after_calc>
                    @php $val = $item->get_funds_after() @endphp
                    @if($val < 0)
                        <div class="bg-red-400 text-black px-2 py-1 rounded-md">
                            {{ is_null($val) ? $val : Number::currency($val) }}
                        </div>
                    @else
                        {{ is_null($val) ? $val : Number::currency($val) }}
                    @endif
                </x-splade-cell>
                <x-splade-cell ff_verified>
                    <x-splade-form default="{{ $item }}" action="{{ route('projects.checkboxUpdate', $item) }}" method="POST" submit-on-change background>
                        <x-splade-checkbox @click.stop="" name="ff_verified" />
                    </x-splade-form>
                </x-splade-cell>
                <x-splade-cell priority.priority>
                    <div class="{{ \App\Models\ProjectPriority::getBgColour($item->priority) }} {{ \App\Models\ProjectPriority::getTextColour($item->priority) }} p-1 rounded">
                        {{ is_null($item->priority) ? '' : $item->priority->priority }}
                    </div>
                </x-splade-cell>
                <x-splade-cell eval_status.status>
                    <div class="{{ \App\Models\ProjectEvaluation::getBgColour($item->eval_status) }} {{ \App\Models\ProjectEvaluation::getTextColour($item->eval_status) }} p-1 rounded">
                        {{ is_null($item->eval_status) ? '' : $item->eval_status->status }}
                    </div>
                </x-splade-cell>
                <x-splade-cell financial_report>
                    <x-splade-form default="{{ $item }}" action="{{ route('projects.checkboxUpdate', $item) }}" method="POST" submit-on-change background>
                        <x-splade-checkbox @click.stop="" name="financial_report" />
                    </x-splade-form>
                </x-splade-cell>
                <x-splade-cell supervisor_review>
                    <x-splade-form default="{{ $item }}" action="{{ route('projects.checkboxUpdate', $item) }}" method="POST" submit-on-change background>
                        <x-splade-checkbox @click.stop="" name="supervisor_review" />
                    </x-splade-form>
                </x-splade-cell>
            </x-splade-table>
        </x-splade-rehydrate>
    </div>
</x-app-layout>