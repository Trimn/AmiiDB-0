<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Edit Staff {{ $staff->first_name }} {{ $staff->last_name }}
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
        <x-splade-form :default="$staff" :action="route('staff.update', $staff)" class="space-y-4">
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
                <x-splade-select name="immigration" :options="$immigrations" label="Immigration" />
            </div>
            <x-splade-input name="amii_start" label="Amii Start Date" date />
            <div class="columns-2">
                <x-splade-input name="start" label="Start Date" date />
                <x-splade-input name="end" label="End Date" date />
            </div>
            <div class="columns-2">
                <x-splade-input name="job_title" label="Job Title" />
                <x-splade-input name="dept" label="Department" />
            </div>
            <div class="columns-2">
                <x-splade-select name="pos_type" label="Position Type" :options="$posTypes" />
                <x-splade-select name="subtype" label="Subtype" :options="$subtypes" />
            </div>
            @php
                $pdfSubtypeIds = [];
                foreach ($subtypes as $id => $name) {
                    if (stripos($name, 'pdf') !== false) {
                        $pdfSubtypeIds[] = $id;
                    }
                }
                $isPdfSubtype = $staff->pos_subtype && stripos($staff->pos_subtype->name, 'pdf') !== false;
            @endphp
            <div v-show="[{{ implode(',', $pdfSubtypeIds) }}].includes(Number(form.subtype))">
                <x-splade-input name="pdf_completed" label="PDF Completed" date />
            </div>
            <div class="columns-2">
                <x-splade-input name="work_permit_start" label="Work Permit Start" date />
                <x-splade-input name="work_permit_end" label="Work Permit End" date />
            </div>
            <x-splade-select name="active" label="Status" :options="$statuses" />
            <x-splade-textarea name="notes" label="Notes" autosize />
            <x-splade-submit label="Update" />
        </x-splade-form>
    </div>
</x-app-layout>