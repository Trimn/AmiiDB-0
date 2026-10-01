<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('My Budget Reports') }}
        </h2>
    </x-slot>

    @include('fellow.partials.view-as-banner')

    <div class="max-w-[95%] mx-auto p-4 sm:p-6 lg:p-8">

        {{-- Fiscal year selector and download button --}}
        @if(!empty($availableFYs))
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-600">Fiscal Year:</span>
                <div class="inline-flex rounded-md shadow-sm">
                    @foreach($availableFYs as $fy)
                        @php $routeParams = array_filter(['fy' => $fy, 'from' => $dateFrom ?? null, 'to' => $dateTo ?? null], fn($v) => $v !== null); @endphp
                        <Link href="{{ route('fellowsView.budget', $routeParams) }}"
                              class="px-3 py-1.5 text-sm border font-medium
                                {{ !$loop->first ? '-ml-px' : 'rounded-l-md' }}
                                {{ $loop->last ? 'rounded-r-md' : '' }}
                                {{ $fy === $fyYear
                                    ? 'bg-green-700 text-white border-green-700 z-10'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                            {{ $fy }}-{{ substr($fy + 1, 2) }}
                        </Link>
                    @endforeach
                </div>
            </div>

            @if(!$speedcodes->isEmpty())
            <a href="{{ route('fellowsView.budgetExportAll', array_filter(['fy' => $fyYear, 'from' => $dateFrom ?? null, 'to' => $dateTo ?? null], fn($v) => $v !== null)) }}"
               class="inline-flex items-center px-4 py-2 bg-green-700 text-white text-sm font-medium rounded-md hover:bg-green-800 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download All (Excel)
            </a>
            @endif
        </div>

        {{-- Custom date-range filter (clamps the selected FY) --}}
        <form method="GET" action="{{ route('fellowsView.budget') }}" class="flex flex-wrap items-end gap-3 mb-6 bg-gray-50 border border-gray-200 rounded-md p-4">
            <input type="hidden" name="fy" value="{{ $fyYear }}" />
            <div class="flex flex-col">
                <label for="from" class="text-xs font-medium text-gray-600 mb-1">From (YYYY-MM-DD)</label>
                <input type="date" id="from" name="from" value="{{ $dateFrom ?? '' }}"
                       class="border border-gray-300 rounded-md shadow-sm px-3 py-1.5 text-sm focus:ring-green-600 focus:border-green-600" />
            </div>
            <div class="flex flex-col">
                <label for="to" class="text-xs font-medium text-gray-600 mb-1">To (YYYY-MM-DD)</label>
                <input type="date" id="to" name="to" value="{{ $dateTo ?? '' }}"
                       class="border border-gray-300 rounded-md shadow-sm px-3 py-1.5 text-sm focus:ring-green-600 focus:border-green-600" />
            </div>
            <button type="submit"
                    class="inline-flex items-center px-3 py-1.5 bg-green-700 text-white text-sm font-medium rounded-md hover:bg-green-800 transition">
                Filter Range
            </button>
            @if(!empty($dateFrom) || !empty($dateTo))
                <a href="{{ route('fellowsView.budget', ['fy' => $fyYear]) }}"
                   class="inline-flex items-center px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 underline">
                    Clear range
                </a>
            @endif
        </form>
        @endif

        <p class="text-sm text-gray-500 mb-4">
            Showing all active projects for FY {{ $fyYear }}-{{ substr($fyYear + 1, 2) }}
            @if(!empty($dateFrom) || !empty($dateTo))
                (actuals from {{ $dateFrom ?? 'FY start' }} to {{ $dateTo ?? 'FY end' }})
            @endif
        </p>

        @if($speedcodes->isNotEmpty())
            <div class="mb-6">
                <x-splade-table :for="$table">
                    @cell('project', $sc)
                        <Link href="{{ route('fellowsView.budgetReport', ['project' => $sc->projectModel, 'fy' => $sc->fy_year]) }}" class="text-blue-600 hover:text-blue-900 underline font-medium">
                            {{ $sc->project }}
                        </Link>
                    @endcell
                </x-splade-table>
            </div>
            
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 shadow-sm overflow-x-auto mt-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Grand Totals for FY {{ $fyYear }}-{{ substr($fyYear + 1, 2) }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Open Bal. as of FY Start</p>
                        <p class="text-lg font-bold text-gray-900">{{ \Illuminate\Support\Number::currency($totOpen) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Future Funding</p>
                        <p class="text-lg font-bold text-gray-900">{{ \Illuminate\Support\Number::currency($totFuture) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">ACT YTD</p>
                        <p class="text-lg font-bold text-gray-900">{{ \Illuminate\Support\Number::currency($totAct) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Commitments</p>
                        <p class="text-lg font-bold text-gray-900">{{ \Illuminate\Support\Number::currency($totCommit) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Funds available AFTER commitments</p>
                        <p class="text-lg font-bold {{ $totFundsAfter < 0 ? 'text-red-600' : 'text-gray-900' }}">{{ \Illuminate\Support\Number::currency($totFundsAfter) }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center text-gray-500 py-12">
                <p class="text-lg">No projects with data for this fiscal year.</p>
            </div>
        @endif
    </div>
</x-app-layout>