<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('My Speedcodes') }}
        </h2>
    </x-slot>

    @include('fellow.partials.view-as-banner')

    <div class="max-w-fit mx-auto p-8">
        <x-splade-table :for="$speedcodes" striped>
            <x-splade-cell projectViewModel.funds_before_calc>
                @php $proj = $item->projectModel @endphp
                @if($proj)
                    @php $val = $proj->get_funds_before() @endphp
                    @if($val < 0)
                        <div class="bg-red-400 text-black px-2 py-1 rounded-md">
                            {{ is_null($val) ? $val : Number::currency($val) }}
                        </div>
                    @else
                        {{ is_null($val) ? $val : Number::currency($val) }}
                    @endif
                @endif
            </x-splade-cell>
            <x-splade-cell projectViewModel.funds_after_calc>
                @php $proj = $item->projectModel @endphp
                @if($proj)
                    @php $val = $proj->get_funds_after() @endphp
                    @if($val < 0)
                        <div class="bg-red-400 text-black px-2 py-1 rounded-md">
                            {{ is_null($val) ? $val : Number::currency($val) }}
                        </div>
                    @else
                        {{ is_null($val) ? $val : Number::currency($val) }}
                    @endif
                @endif
            </x-splade-cell>
        </x-splade-table>
    </div>
</x-app-layout>