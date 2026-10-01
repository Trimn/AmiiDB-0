<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Appointment') }}
        </h2>
    </x-slot>
    
    <x-splade-modal stay>
        <div class="max-w-7xl mx-auto p-8">
            <x-splade-form :action="route('staff.appt.store', $staff)" default="{ benefits: 0 }" class="space-y-4">
                <x-splade-input date name="start" label="Start" />
                <x-splade-input date name="end" label="End" />
                <x-splade-input name="rate" label="Rate" />
                <x-splade-checkbox name="hourly" label="Hourly?" />
                <x-splade-input name="grade" label="Grade" v-show="form.hourly" />
                <x-splade-input name="step" label="Step" v-show="form.hourly" />
                <x-splade-input name="hours" label="Hours per week" />
                <x-splade-input name="benefits" label="Benefits" v-model="form.benefits" />
                <x-splade-select name="speedcode_1" label="Speedcode 1" :options="\App\Models\Speedcodes::options()" choices />
                <x-splade-input name="speedcode_1_prc" label="Speedcode 1 %" />
                <x-splade-select name="speedcode_2" label="Speedcode 2" :options="\App\Models\Speedcodes::options()" choices />
                <x-splade-input name="speedcode_2_prc" label="Speedcode 2 %" />
                <x-splade-select name="speedcode_3" label="Speedcode 3" :options="\App\Models\Speedcodes::options()" choices />
                <x-splade-input name="speedcode_3_prc" label="Speedcode 3 %" />
                <x-splade-submit label="Add" />
            </x-splade-form>
        </div>
    </x-splade-modal>
</x-app-layout>