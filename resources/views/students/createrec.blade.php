<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Students') }}
        </h2>
    </x-slot>
    
    <x-splade-modal>
        <div class="max-w-7xl mx-auto p-8">
            {{-- <x-splade-form :for="$form" stay /> --}}

            <x-splade-form :action="route('students.create_record', $person)">
                <x-splade-select label="Program" name="program" :options="\App\Models\Program::options()" />
                <x-splade-checkbox name="phd_post" label="Ph.D. Post" v-show="form.program == 'PhD'" />
                <x-splade-select label="Program Start" :options="\App\Models\Terms::options()" name="program_start" />
                <x-splade-input label="Department" name="dept" />
                <x-splade-input label="Current Salary Step" name="curr_step" />
                <x-splade-input label="Term Adjust" name="term_adj" />
                <x-splade-select label="GF Last" name="gf_last" :options="\App\Models\Terms::options()" />
                <x-splade-select label="Status" name="active" :options="\App\Models\Status::options()" />
                <x-splade-textarea label="Notes" name="notes" />
                <x-splade-submit label="Submit" />
            </x-splade-form>
        </div>
    </x-splade-modal>
</x-app-layout>