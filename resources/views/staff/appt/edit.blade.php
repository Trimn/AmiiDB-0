<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Appointment') }}
        </h2>
    </x-slot>
    
    <x-splade-modal stay>
        <div class="max-w-7xl mx-auto p-8">
            <x-splade-form :action="route('staff.appt.update', $appt_id)" class="space-y-4" :default="$appt">
                <x-splade-input date name="start" label="Start" :readonly="!Auth::user()->can('edit')" />
                <x-splade-input date name="end" label="End" :readonly="!Auth::user()->can('edit')" />
                <x-splade-input name="rate" label="Rate" :readonly="!Auth::user()->can('edit')" />
                <x-splade-checkbox name="hourly" label="Hourly?" :readonly="!Auth::user()->can('edit')" />
                <x-splade-input name="grade" label="Grade" v-show="form.hourly" :readonly="!Auth::user()->can('edit')" />
                <x-splade-input name="step" label="Step" v-show="form.hourly" :readonly="!Auth::user()->can('edit')" />
                <x-splade-input name="hours" label="Hours per week" :readonly="!Auth::user()->can('edit')" />
                <x-splade-input name="benefits" label="Benefits" :readonly="!Auth::user()->can('edit')" />
                <x-splade-select name="speedcode_1" label="Speedcode 1" :options="Auth::user()->can('edit') ? \App\Models\Speedcodes::options() : [$appt['speedcode_1'] => $appt['speedcode_1']]" choices :disabled="!Auth::user()->can('edit')" />
                <x-splade-input name="speedcode_1_prc" label="Speedcode 1 %" :readonly="!Auth::user()->can('edit')" />
                <x-splade-select name="speedcode_2" label="Speedcode 2" :options="Auth::user()->can('edit') ? \App\Models\Speedcodes::options() : [$appt['speedcode_2'] => $appt['speedcode_2']]" choices :disabled="!Auth::user()->can('edit')" />
                <x-splade-input name="speedcode_2_prc" label="Speedcode 2 %" :readonly="!Auth::user()->can('edit')" />
                <x-splade-select name="speedcode_3" label="Speedcode 3" :options="Auth::user()->can('edit') ? \App\Models\Speedcodes::options() : [$appt['speedcode_3'] => $appt['speedcode_3']]" choices :disabled="!Auth::user()->can('edit')" />
                <x-splade-input name="speedcode_3_prc" label="Speedcode 3 %" :readonly="!Auth::user()->can('edit')" />
                @if(Auth::user()->can('edit'))
                <x-splade-submit label="Update" />
                @endif
            </x-splade-form>
        </div>
    </x-splade-modal>
</x-app-layout>