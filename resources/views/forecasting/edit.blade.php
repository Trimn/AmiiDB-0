<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Edit Project
        </h2>
    </x-slot>
    <x-splade-modal max-width="7xl">
        <div class="grid grid-cols-5 gap-4">
            <x-splade-form :for="$form" @success="$splade.emit('projects-updated')" />
            <div class="col-span-4">
                <div class="py-4">
                    <h2>Student Appointments (Estimated commitment: {{ \Illuminate\Support\Number::currency($est_students ?? 0) }})</h2>
                    <x-splade-table :for="$students" />
                </div>
                <div class="py-4">
                    <h2>Staff Appointments (Estimated commitment: {{ \Illuminate\Support\Number::currency($est_staff ?? 0) }})</h2>
                    <x-splade-table :for="$staff" />
                </div>
                <div class="py-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2>Current Budgets</h2>
                        @if(Auth::user()->can('edit'))
                            <Link slideover href="{{ route('budgets.create.project', $project) }}" class="p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Add Budget</Link>
                        @endif
                    </div>
                    <x-splade-table :for="$budgets" />
                </div>
            </div>
        </div>
    </x-splade-modal>
</x-app-layout>