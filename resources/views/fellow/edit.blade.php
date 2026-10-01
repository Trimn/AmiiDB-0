<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Speedcode Details
        </h2>
    </x-slot>

    @include('fellow.partials.view-as-banner')

    <x-splade-modal max-width="7xl">
        <div class="grid grid-cols-4 gap-4">
            <div class="col-span-4">
                <div class="py-4">
                    <h2>Student Appointments (Estimated commitment: {{ \Illuminate\Support\Number::currency($est_students ?? 0) }})</h2>
                    <x-splade-table :for="$students" />
                </div>
                <div class="py-4">
                    <h2>Staff Appointments (Estimated commitment: {{ \Illuminate\Support\Number::currency($est_staff ?? 0) }})</h2>
                    <x-splade-table :for="$staff" />
                </div>
            </div>
        </div>
        <div class="mx-auto">
            <x-splade-form :default="$project" action="{{ route('fellowsView.updateProject', $project) }}">
                <x-splade-textarea class="space-y-4" name="notes" label="Notes" />
                <x-splade-submit>Update</x-splade-submit>
            </x-splade-form>
        </div>
    </x-splade-modal>
</x-app-layout>