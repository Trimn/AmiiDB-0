<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Add Permission
        </h2>
    </x-slot>

    <x-splade-modal stay>
        <div class="max-w-7xl mx-auto p-8">
            <x-splade-form action="{{ route('admin.createPerm') }}" method="POST">
                <x-splade-input name="name" label="Name" />
                <x-splade-submit label="Submit" />
            </x-splade-form>
        </div>
    </x-splade-modal>
</x-app-layout>