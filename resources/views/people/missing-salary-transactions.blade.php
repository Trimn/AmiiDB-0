<x-splade-modal max-width="7xl">
    <div class="p-6">
        <h2 class="text-xl font-bold mb-4">Transactions for UID: {{ $uid }} - Project: {{ $project }} - Program: {{ $program }}</h2>

        <x-splade-form :default="['uid' => $notes->uid ?? $uid, 'notes' => $notes->notes, 'who_id' => $notes->who_id]" action="{{ route('people.missing_salary_notes') }}" method="POST" background>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <x-splade-textarea name="notes" label="Notes" autosize />
                <x-splade-select name="who_id" label="Who" :options="$projectTeamOptions" />
            </div>
            <div class="mb-6">
                <x-splade-submit label="Save" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" />
            </div>
        </x-splade-form>

        <x-splade-table :for="$table" />
    </div>
</x-splade-modal>
