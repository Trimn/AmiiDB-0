<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Student/Staff Metrics') }}
        </h2>
    </x-slot>

    <x-splade-toggle>
        <div class="max-w-fit mx-8 my-4 bg-amber-300 border border-orange-300 rounded-md" v-if="!toggled">
            <button class="float-right px-2" @click.prevent="toggle">&times;</button>
            <div class="text-sm p-4">
                <p class="py-2">
                    This table shows all student and staff appointments that overlap the start and end dates. Type is determined by their program or staff type. Affiliate students and staff are also included in this table.
                </p>
            </div>
        </div>
    </x-splade-toggle>

    <div class="w-1/2 p-4 mx-auto">
        <x-splade-form :default="['start' => $start, 'end' => $end]" method="GET" action="{{ route('metrics.appts') }}" submit-on-change>
            <div class="grid grid-cols-2 gap-4">
                <x-splade-input name="start" label="Start" date />
                <x-splade-input name="end" label="End" date />
            </div>
        </x-splade-form>
    </div>
    <div class="max-w-fit mx-auto p-8">
        <x-splade-table :for="$appts" striped />
    </div>
</x-app-layout>