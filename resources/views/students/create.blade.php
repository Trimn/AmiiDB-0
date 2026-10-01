<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Students') }}
        </h2>
    </x-slot>
    
    <x-splade-modal>
        <div class="max-w-7xl mx-auto p-8">
            {{-- <x-splade-form :for="$form" stay /> --}}
        

            <x-splade-form :action="route('students.create')" stay>
                <x-splade-input name="uid" label="University ID" />
                <x-splade-input label="Last Name" name="last_name" />
                <x-splade-input label="First Name" name="first_name" />
                <x-splade-input type="email" label="Alternate Email" name="email" />
                <x-splade-input label="CCID" name="ccid" />
                <x-splade-select label="Gender" name="gender" :options="\App\Models\Gender::options()" />
                <x-splade-select label="Citizenship" name="citizenship" :options="\App\Models\Countries::options()" />
                <x-splade-select label="Immigration" name="immigration" :options="\App\Models\Immigration::options()" />
                <x-splade-input label="Amii Start Date" name="amii_start" date />
                <x-splade-select label="Primary Supervisor" name="supervisor" :options="\App\Models\Fellows::options()" />
                <x-splade-input label="Secondary supervisor" name="supervisor2" />
                <x-splade-select label="Permit Type" name="wp_type" :options="\App\Models\People::wp_types()" choices />
                <x-splade-input label="Permit Start" name="wp_start" date />
                <x-splade-input label="Permit End" name="wp_end" date />
                <x-splade-select label="Program" name="program" :options="\App\Models\Program::options()" />
                <x-splade-checkbox name="phd_post" label="Ph.D. Post" v-show="form.program == 'PhD'" />
                <x-splade-select label="Program Start" :options="\App\Models\Terms::options()" name="program_start" />
                <x-splade-input label="Department" name="dept" />
                <x-splade-input label="Current Salary Step" name="curr_step" />
                <x-splade-input label="Term Adjust" name="term_adj" />
                <x-splade-select label="GF Last" name="gf_last" :options="\App\Models\Terms::options()" />
                <x-splade-input label="Final Exam Pass Date" name="convocation" date />
                <x-splade-select label="Status" name="active" :options="\App\Models\Status::options()" />
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