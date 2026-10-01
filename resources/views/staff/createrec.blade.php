<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Staff') }}
        </h2>
    </x-slot>
    
    <x-splade-modal>
        <div class="max-w-7xl mx-auto p-8">
            {{-- <x-splade-form :for="$form" stay /> --}}
        

            <x-splade-form :action="route('staff.create_record', $person)">
                <x-splade-input name="job_title" label="Job Title" />
                <x-splade-input name="dept" label="Department" />
                <x-splade-select label="Position type" name="pos_type" :options="\App\Models\StaffType::options()" />
                <x-splade-select label="Position subtype" name="subtype" :options="\App\Models\StaffSubtype::options()" />
                @php
                    $pdfSubtypeIds = [];
                    foreach (\App\Models\StaffSubtype::options() as $id => $name) {
                        if (stripos($name, 'pdf') !== false) {
                            $pdfSubtypeIds[] = $id;
                        }
                    }
                @endphp
                <div v-show="[{{ implode(',', $pdfSubtypeIds) }}].includes(Number(form.subtype))">
                    <x-splade-input label="PDF Completed" name="pdf_completed" date />
                </div>
                <x-splade-select label="Status" name="active" :options="\App\Models\Status::options()" />
                <x-splade-textarea label="Notes" name="notes" />
                <x-splade-submit label="Submit" />
            </x-splade-form>
        </div>

    </x-splade-modal>
</x-app-layout>