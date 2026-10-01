<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Fellows') }}
        </h2>
    </x-slot>
    
    <x-splade-modal>
        <div class="max-w-7xl mx-auto p-8">
            <x-splade-form :for="$form" default="{ temporary_id: false }" stay>
                <x-splade-defer url="{{ route('people.random_uid') }}" method="GET" watch-value="form.temporary_id" watch-debounce="1000"
                    @success="function (response) {
                        if(form.temporary_id) {
                            form.uid = response.uid;
                        }
                        else {
                            form.uid = '';
                        }
                    }" />
            </x-splade-form>
        </div>
    </x-splade-modal>
</x-app-layout>