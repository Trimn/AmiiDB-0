<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Add Missing Person') }}
        </h2>
    </x-slot>

    <x-splade-modal>
        <div class="max-w-7xl mx-auto p-8">
            <x-splade-form :default="[
                'person_type' => '',
                'uid' => $uid,
                'last_name' => $lastName,
                'first_name' => $firstName,
                'import_name' => $importName,
            ]" :action="route('people.missing_salary_add')" method="POST">

                {{-- Person type selector --}}
                <div class="mb-6">
                    <x-splade-radios name="person_type" label="Person Type" :options="['student' => 'Student', 'staff' => 'Staff']" inline required
                        v-on:change="(function () { var raw = form.import_name; if (!raw) { return; } var i = raw.indexOf(','); if (i >= 0) { form.last_name = raw.slice(0, i).trim(); form.first_name = raw.slice(i + 1).trim(); } else { form.last_name = raw.trim(); form.first_name = ''; } })()" />
                </div>

                {{-- Common person fields --}}
                <div v-show="form.person_type">
                    <h3 class="text-lg font-semibold mb-3 border-b pb-1">Person Details</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ __('Fields marked with * are required.') }}
                        <span class="block mt-1 text-gray-500">{{ __('For staff, Gender, Citizenship, Immigration, and Primary Supervisor are required as well.') }}</span>
                    </p>
                    <x-splade-input name="uid" label="University ID" required />
                    <x-splade-input label="Last Name" name="last_name" required />
                    <x-splade-input label="First Name" name="first_name" required />
                    <x-splade-input type="email" label="Alternate Email" name="email" />
                    <x-splade-input label="CCID" name="ccid" required />
                    <div class="mb-4">
                        <span class="block mb-1 text-gray-700 font-sans text-sm">
                            {{ __('Gender') }}
                            <span v-show="form.person_type === 'staff'" class="text-red-600" title="{{ __('Required') }}">*</span>
                        </span>
                        <x-splade-select name="gender" label="" :options="\App\Models\Gender::options()" v-bind:required="form.person_type === 'staff'" />
                    </div>
                    <div class="mb-4">
                        <span class="block mb-1 text-gray-700 font-sans text-sm">
                            {{ __('Citizenship') }}
                            <span v-show="form.person_type === 'staff'" class="text-red-600" title="{{ __('Required') }}">*</span>
                        </span>
                        <x-splade-select name="citizenship" label="" :options="\App\Models\Countries::options()" v-bind:required="form.person_type === 'staff'" />
                    </div>
                    <div class="mb-4">
                        <span class="block mb-1 text-gray-700 font-sans text-sm">
                            {{ __('Immigration') }}
                            <span v-show="form.person_type === 'staff'" class="text-red-600" title="{{ __('Required') }}">*</span>
                        </span>
                        <x-splade-select name="immigration" label="" :options="\App\Models\Immigration::options()" v-bind:required="form.person_type === 'staff'" />
                    </div>
                    <x-splade-input label="Amii Start Date" name="amii_start" date />
                    <div class="mb-4">
                        <span class="block mb-1 text-gray-700 font-sans text-sm">
                            {{ __('Primary Supervisor') }}
                            <span v-show="form.person_type === 'staff'" class="text-red-600" title="{{ __('Required') }}">*</span>
                        </span>
                        <x-splade-select name="supervisor" label="" :options="\App\Models\Fellows::options()" v-bind:required="form.person_type === 'staff'" />
                    </div>
                    <x-splade-input label="Secondary supervisor" name="supervisor2" />
                    <x-splade-select label="Permit Type" name="wp_type" :options="\App\Models\People::wp_types()" choices />
                    <x-splade-input label="Permit Start" name="wp_start" date />
                    <x-splade-input label="Permit End" name="wp_end" date />

                    {{-- Student-specific fields (v-if so required fields are not validated when adding staff) --}}
                    <div v-if="form.person_type == 'student'">
                        <h3 class="text-lg font-semibold mt-6 mb-3 border-b pb-1">Student Details</h3>
                        <x-splade-select label="Program" name="program" :options="\App\Models\Program::options()" required />
                        <x-splade-checkbox name="phd_post" label="Ph.D. Post" v-show="form.program == 'PhD'" />
                        <x-splade-select label="Program Start" :options="\App\Models\Terms::options()" name="program_start" required />
                        <x-splade-input label="Department" name="dept" required />
                        <x-splade-input label="Current Salary Step" name="curr_step" required />
                        <x-splade-input label="Term Adjust" name="term_adj" />
                        <x-splade-select label="GF Last" name="gf_last" :options="\App\Models\Terms::options()" />
                        <x-splade-input label="Final Exam Pass Date" name="convocation" date />
                    </div>

                    {{-- Staff-specific fields (v-if so required fields are not validated when adding student) --}}
                    <div v-if="form.person_type == 'staff'">
                        <h3 class="text-lg font-semibold mt-6 mb-3 border-b pb-1">Staff Details</h3>
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
                    </div>

                    <x-splade-select label="Status" name="active" :options="\App\Models\Status::options()" required />
                    <x-splade-textarea label="Notes" name="notes" />
                    <x-splade-submit label="Submit" />

                    <x-splade-defer url="{{ route('people.find') }}" method="POST" request="{ uid: form.uid }" watch-value="form.uid" watch-debounce="1000"
                        @success="function (response) {
                            if (!response || !response.id) {
                                return;
                            }
                            form.last_name = response.last_name;
                            form.first_name = response.first_name;
                            form.email = response.email;
                            form.ccid = response.ccid;
                            form.gender = response.gender;
                            form.citizenship = response.citizenship;
                            form.immigration = response.immigration;
                            form.amii_start = response.amii_start;
                            form.supervisor = response.supervisor;
                            form.supervisor2 = response.supervisor2;
                            form.wp_type = response.wp_type;
                            form.wp_start = response.wp_start;
                            form.wp_end = response.wp_end;
                        }" />
                </div>

            </x-splade-form>
        </div>
    </x-splade-modal>
</x-app-layout>
