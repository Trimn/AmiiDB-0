<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Edit Student {{ $student->first_name }} {{ $student->last_name }}
        </h2>
        {{-- <div class="inline float-right -my-7 p-2">
            <button data-bs-toggle="modal" data-bs-target="#confirm" class="rounded-md p-3 bg-red-700 text-gray-200">Delete</a>
        </div>
        <x-splade-modal id="confirm" title="Confirm deletion" :centered="true">
            <x-slot name="body">
                Are you sure you want to delete {{ $student->first_name }} {{ $student->last_name }}?
            </x-slot>
            <x-slot name="footer">
                <button type="button" class="rouded-md bg-gray-700 text-gray-200" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="rouded-md bg-red-700 text-gray-200">Delete</button>
            </x-slot>
        </x-splade-modal> --}}
    </x-slot>

    <div class="max-w-7xl mx-auto p-8">
        <x-splade-form :default="$student" :action="route('student.update', $student)" class="space-y-4">
            <div class="columns-2">
                <x-splade-input name="last_name" label="Last Name" />
                <x-splade-input name="first_name" label="First Name" />
            </div>
            <x-splade-input name="email" label="Alternate Email" />
            <x-splade-input name="uid" label="University ID" />
            <x-splade-input name="ccid" label="CCID" />
            <x-splade-select name="gender" :options="$genders" label="Gender" />
            <div class="columns-2">
                <x-splade-input name="citizenship" label="Citizenship" />
                <x-splade-select name="immigration" :options="$immigrations" label="Immigration" choices />
            </div>
            <x-splade-input name="amii_start" label="Amii Start Date" date />
            <x-splade-select name="program" :options="$programs" label="Program" />
            <x-splade-select name="program_start" :options="$terms" label="Program Start" />
            <x-splade-input name="dept" label="Dept" />
            <x-splade-input name="convocation" label="Final Exam Pass Date" date />
            <x-splade-select name="active" :options="$statuses" label="Status" />
            <x-splade-textarea name="notes" label="Notes" autosize />
            <x-splade-submit label="Update" />
        </x-splade-form>
    </div>
</x-app-layout>