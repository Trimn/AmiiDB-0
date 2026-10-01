<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Add Project
        </h2>
    </x-slot>

    <x-splade-modal>
        <div class="max-w-7xl mx-auto p-8">
            <x-splade-form :for="$form" stay @success="$splade.emit('projects-updated')">
                <x-splade-defer url="{{ route('speedcodes.find') }}" method="POST" request="{ code: form.code }" watch-value="form.code" watch-debounce="1000"
                    @success="function (response) {
                        form.fellow = response.fellow;
                        form.description = response.description;
                        form.project = response.project;
                        form.combo_code = response.combo_code;
                        form.gender = response.gender;
                        form.award_start = response.award_start;
                        form.award_end = response.award_end;
                        form.status = response.status;
                    }" />
            </x-splade-form>
        </div>
    </x-splade-modal>
</x-app-layout>