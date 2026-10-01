<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Budget Spending Summary') }}
        </h2>
    </x-slot>

    @include('fellow.partials.view-as-banner')

    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Back link --}}
        <div class="mb-4">
            @php $backParams = array_filter(['fy' => $fyYear, 'from' => $dateFrom ?? null, 'to' => $dateTo ?? null], fn($v) => $v !== null); @endphp
            <Link href="{{ route('fellowsView.budget', $backParams) }}" class="text-sm text-blue-600 hover:underline">
                &larr; Back to all projects
            </Link>
        </div>

        {{-- Project tabs --}}
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-4 overflow-x-auto" aria-label="Projects">
                @foreach($speedcodes as $sc)
                    @php $proj = $sc->projectModel; @endphp
                    @if($proj)
                        @php $tabParams = array_filter(['project' => $proj, 'fy' => $fyYear, 'from' => $dateFrom ?? null, 'to' => $dateTo ?? null], fn($v) => $v !== null); @endphp
                        <Link href="{{ route('fellowsView.budgetReport', $tabParams) }}"
                              class="whitespace-nowrap py-3 px-4 border-b-2 font-medium text-sm
                                {{ $proj->id === $project->id
                                    ? 'border-green-600 text-green-700'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            {{ $sc->code }} — {{ $sc->description }}
                        </Link>
                    @endif
                @endforeach
            </nav>
        </div>

        {{-- Project header --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2">
                <h3 class="text-xl font-bold text-gray-800">Research Expenditures</h3>

                {{-- Fiscal year selector --}}
                @if(!empty($availableFYs))
                <div class="flex items-center space-x-2 mt-2 sm:mt-0">
                    <span class="text-sm font-medium text-gray-600">Fiscal Year:</span>
                    <div class="inline-flex rounded-md shadow-sm">
                        @foreach($availableFYs as $fy)
                            @php $fyParams = array_filter(['project' => $project, 'fy' => $fy, 'from' => $dateFrom ?? null, 'to' => $dateTo ?? null], fn($v) => $v !== null); @endphp
                            <Link href="{{ route('fellowsView.budgetReport', $fyParams) }}"
                                  class="px-3 py-1 text-sm border font-medium
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
                @endif
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-semibold text-gray-600">Project #:</span>
                    <span class="ml-1 bg-yellow-200 px-2 py-0.5 rounded font-mono">{{ optional($project->speedcode)->project }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-600">Project Name:</span>
                    <span class="ml-1 bg-yellow-200 px-2 py-0.5 rounded">{{ optional($project->speedcode)->description }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-600">Speed Code:</span>
                    <span class="ml-1 bg-green-200 px-2 py-0.5 rounded font-mono">{{ $project->code }}</span>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-2">
                FY {{ $fyYear }}-{{ substr($fyYear + 1, 2) }} &mdash;
                Actual Year to Date: {{ $fyStart }} to {{ $fyEnd }}
                @if(!empty($dateFrom) || !empty($dateTo))
                    <span class="text-gray-400">(custom range within FY)</span>
                @endif
            </p>

            {{-- Custom date-range filter (clamps the selected FY) --}}
            <div class="mt-4 border-t border-gray-100 pt-4">
                <form method="GET" action="{{ route('fellowsView.budgetReport', ['project' => $project]) }}"
                      class="flex flex-wrap items-end gap-3">
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
                        <a href="{{ route('fellowsView.budgetReport', ['project' => $project, 'fy' => $fyYear]) }}"
                           class="inline-flex items-center px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 underline">
                            Clear range
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Financial Summary --}}
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 shadow-sm overflow-x-auto mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Totals for FY {{ $fyYear }}-{{ substr($fyYear + 1, 2) }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Open Bal. as of FY Start</p>
                    <p class="text-lg font-bold text-gray-900">{{ \Illuminate\Support\Number::currency($openBalanceFyStart) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Future Funding</p>
                    <p class="text-lg font-bold text-gray-900">{{ \Illuminate\Support\Number::currency($futureFunding) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">ACT YTD</p>
                    <p class="text-lg font-bold text-gray-900">{{ \Illuminate\Support\Number::currency($actYtd) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Commitments</p>
                    <p class="text-lg font-bold text-gray-900">{{ \Illuminate\Support\Number::currency($commitments) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Funds available AFTER commitments</p>
                    <p class="text-lg font-bold {{ $fundsAfter < 0 ? 'text-red-600' : 'text-gray-900' }}">{{ \Illuminate\Support\Number::currency($fundsAfter) }}</p>
                </div>
            </div>
        </div>

        {{-- Expenditure matrix table --}}
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-green-700 text-white">
                        <th class="px-3 py-2 text-left font-semibold" colspan="2"></th>
                        <th class="px-3 py-2 text-left font-semibold w-16"></th>
                        @foreach($months as $monthNum => $monthName)
                            <th class="px-3 py-2 text-right font-semibold">{{ $monthName }}</th>
                        @endforeach
                        <th class="px-3 py-2 text-right font-semibold bg-green-800">Total</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Salaries & Benefits header --}}
                    <tr class="bg-green-100 font-bold italic">
                        <td class="px-3 py-2" colspan="3">Salaries &amp; Benefits</td>
                        @foreach($months as $monthNum => $monthName)
                            <td class="px-3 py-2 text-right">
                                {{ isset($salaryMonthTotals[$monthNum]) ? Number::currency($salaryMonthTotals[$monthNum]) : '-' }}
                            </td>
                        @endforeach
                        <td class="px-3 py-2 text-right bg-green-200 font-bold">
                            {{ Number::currency($salaryGrandTotal) }}
                        </td>
                    </tr>

                    {{-- Individual staff rows --}}
                    @foreach($salaryPeople as $person)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 italic">
                        <td class="px-3 py-1.5 text-gray-600 pl-6">{{ $person['uid'] }}</td>
                        <td class="px-3 py-1.5">{{ $person['name'] }}</td>
                        <td class="px-3 py-1.5 text-left italic text-gray-700">
                            @if($person['type'])
                                {{ $person['type'] }}
                            @endif
                        </td>
                        @foreach($months as $monthNum => $monthName)
                            <td class="px-3 py-1.5 text-right font-mono text-gray-700">
                                {{ isset($person['months'][$monthNum]) ? Number::currency($person['months'][$monthNum]) : '-' }}
                            </td>
                        @endforeach
                        <td class="px-3 py-1.5 text-right font-mono font-semibold bg-gray-50">
                            {{ Number::currency($person['total']) }}
                        </td>
                    </tr>
                    @endforeach

                    @if(empty($salaryPeople))
                    <tr class="border-b border-gray-100">
                        <td class="px-3 py-1.5 pl-6 text-gray-400 italic" colspan="{{ 3 + count($months) + 1 }}">
                            No salary data for this period.
                        </td>
                    </tr>
                    @endif

                    {{-- Non-salary category rows --}}
                    @foreach($categories as $catName => $catData)
                    <tr class="bg-green-100 font-bold border-t border-gray-200">
                        <td class="px-3 py-2" colspan="3">{{ $catName }}</td>
                        @foreach($months as $monthNum => $monthName)
                            <td class="px-3 py-2 text-right">
                                {{ isset($catData['months'][$monthNum]) ? Number::currency($catData['months'][$monthNum]) : '-' }}
                            </td>
                        @endforeach
                        <td class="px-3 py-2 text-right bg-green-200 font-bold">
                            {{ Number::currency($catData['total']) }}
                        </td>
                    </tr>
                    @endforeach

                    {{-- Grand total row --}}
                    <tr class="bg-green-700 text-white font-bold border-t-2 border-green-800">
                        <td class="px-3 py-2" colspan="3">Total</td>
                        @foreach($months as $monthNum => $monthName)
                            <td class="px-3 py-2 text-right">
                                {{ isset($grandMonthTotals[$monthNum]) ? Number::currency($grandMonthTotals[$monthNum]) : '-' }}
                            </td>
                        @endforeach
                        <td class="px-3 py-2 text-right bg-green-800">
                            {{ Number::currency($grandTotal) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
