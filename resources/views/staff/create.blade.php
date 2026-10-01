<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Staff') }}
        </h2>
    </x-slot>
    
    <x-splade-modal>
        <div class="max-w-7xl mx-auto p-8">
            {{-- <x-splade-form :for="$form" stay /> --}}
        

            <x-splade-form default="{ uid: '' }" :action="route('staff.create')">
                <x-splade-input name="uid" label="University ID" required />
                <x-splade-input label="Last Name" name="last_name" required />
                <x-splade-input label="First Name" name="first_name" required />
                <x-splade-input type="email" label="Alternate Email" name="email" />
                <x-splade-input label="CCID" name="ccid" required />
                <x-splade-select label="Gender" name="gender" :options="\App\Models\Gender::options()" required />
                <x-splade-select label="Citizenship" name="citizenship" :options="\App\Models\Countries::options()" required />
                <x-splade-select label="Immigration" name="immigration" :options="\App\Models\Immigration::options()" required />
                <x-splade-input label="Amii Start" name="amii_start" date />
                <x-splade-select label="Primary Supervisor" name="supervisor" :options="\App\Models\Fellows::options()" required />
                <x-splade-input label="Secondary supervisor" name="supervisor2" />
                <x-splade-select label="Permit Type" name="wp_type" :options="\App\Models\People::wp_types()" choices />
                <x-splade-input label="Permit Start" name="wp_start" date />
                <x-splade-input label="Permit End" name="wp_end" date />
                <x-splade-input name="job_title" label="Job Title" />
                <x-splade-input name="dept" label="Department" />
                <x-splade-select label="Position type" name="pos_type" :options="\App\Models\StaffType::options()" required />
                <x-splade-select label="Position subtype" name="subtype" :options="\App\Models\StaffSubtype::options()" required />
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
                <x-splade-select label="Status" name="active" :options="\App\Models\Status::options()" required />
                <x-splade-textarea label="Notes" name="notes" />
                <x-splade-submit label="Submit" />
                <x-splade-defer url="{{ route('people.find') }}" method="POST" request="{ uid: form.uid }" watch-value="form.uid" watch-debounce="1000"
                    @success="function (response) {
                        form.last_name = response.last_name;
                        form.first_name = response.first_name;
                        form.email = response.email;
                        form.ccid = response.ccid;
                        form.gender = response.gender;
                        form.citizenship = response.citizenship;
                        form.immigration = response.immigration;
                        form.amii_start = response.amii_start;
                        form.supervisor = response.supervisor;
                        form.supservisor2 = response.supvisor2;
                        form.wp_type = response.wp_type;
                        form.wp_start = response.wp_start;
                        form.wp_end = response.wp_end;
                    }" />
            </x-splade-form>
        </div>

    </x-splade-modal>
</x-app-layout>