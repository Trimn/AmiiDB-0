@seoTitle('Edit Payment')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Edit Payment
        </h2>
    </x-slot>
    <x-splade-modal>
        <x-splade-form :for="$form">
            <x-splade-defer url="{{ route('people.find') }}" method="POST" request="{ uid: form.uid }" watch-value="form.uid" watch-debounce="1000"
                    @success="function (response) {
                        form.last_name = response.last_name;
                        form.first_name = response.first_name;
                        form.ccid = response.ccid;
                        form.supervisor = response.supervisor;
                        form.supervisor2 = response.supervisor2;
                    }" />
        </x-splade-form>
    </x-splade-modal>
</x-app-layout>