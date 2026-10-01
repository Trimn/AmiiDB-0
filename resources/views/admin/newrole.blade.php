<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Add Role
        </h2>
    </x-slot>

    <x-splade-modal stay>
        <div class="max-w-7xl mx-auto p-8">
            <x-splade-form action="{{ route('admin.createRole') }}" method="POST">
                <x-splade-input name="name" label="Name" />
                <x-splade-select name="permissions[]" label="Permissions" :options="\Spatie\Permission\Models\Permission::all()->pluck('name', 'id')" multiple choices />
                <x-splade-submit label="Submit" />
            </x-splade-form>
        </div>
    </x-splade-modal>
</x-app-layout>