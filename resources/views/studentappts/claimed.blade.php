<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Amii Claimed Students') }}
        </h2>
    </x-slot>

    <x-splade-toggle>
        <div class="max-w-fit mx-8 my-4 bg-amber-300 border border-orange-300 rounded-md" v-if="!toggled">
            <button class="float-right px-2" @click.prevent="toggle">&times;</button>
            <div class="text-sm p-4">
                <p class="py-2">
                    This report displays all appointments by term. All student program statuses are shown. Multiple entries for the same student indicate a Program or Combo Code Change during that term.
                </p>
                <p>
                    The filter icon below can be used to view a specific term. You can export the table as an excel file by clicking the gear.
                </p>
            </div>
        </div>
    </x-splade-toggle>

    <div class="max-w-fit mx-auto px-8 py-4">
        <x-splade-table :for="$appts" striped />
    </div>
</x-app-layout>