<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Add Visitor
        </h2>
    </x-slot>

    <x-splade-modal stay>
        <div class="max-w-7xl mx-auto p-8">
            <x-splade-form :for="$form" stay>
                <x-splade-defer url="{{ route('people.find') }}" method="POST" request="{ uid: form.uid }" watch-value="form.uid" watch-debounce="1000"
                    @success="function (response) {
                        form.last_name = response.last_name;
                        form.first_name = response.first_name;
                        form.ccid = response.ccid;
                        form.gender = response.gender;
                    }" />
            </x-splade-form>
        </div>
    </x-splade-modal>
</x-app-layout>