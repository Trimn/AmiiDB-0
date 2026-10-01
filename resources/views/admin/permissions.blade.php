<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Permissions') }}
        </h2>
    </x-slot>

    <div class="max-w-fit mx-auto p-8">
        <h2 class="inline-block">Roles</h2>
        <Link slideover href="{{ route('admin.newRole') }}" class="float-right p-2 m-4 bg-green-900 text-gray-200 rounded-md">New Role</Link>
    </div>

    <div class="max-w-fit mx-auto p-8">
        <div class="table">
            <div class="table-row">
                <div class="table-cell p-2">Role</div>
                <div class="table-cell p-2">Permissions</div>
                <div class="table-cell p-2">Actions</div>
            </div>
            @foreach(\Spatie\Permission\Models\Role::all() as $item)
                <div class="table-row">
                    <div class="table-cell p-1 relative">
                        <x-splade-form action="{{ route('admin.roleUpdate', $item->id) }}" :default="$item" submit-on-change background debounce="300">
                            <x-splade-input name="name" />
                            <x-splade-transition show="form.recentlySuccessful" animation="opacity">
                                <div class="absolute top-2 right-2 z10">&#x2705;</div>
                            </x-splade-transition>
                        </x-splade-form>
                    </div>
                    <div class="table-cell p-1 relative">
                        <x-splade-form action="{{ route('admin.roleUpdate', $item->id) }}" :default="$item" submit-on-change background debounce="300">
                            <x-splade-select name="permissions[]" :options="\Spatie\Permission\Models\Permission::all()->pluck('name', 'id')" multiple relation choices />
                            <x-splade-transition show="form.recentlySuccessful" animation="opacity">
                                <div class="absolute top-2 right-2 z10">&#x2705;</div>
                            </x-splade-transition>
                        </x-splade-form>
                    </div>
                    <div class="table-cell p-1">
                        <Link class="bg-red-400 p-2 rounded border" href="{{ route('admin.deleteRole', $item->id) }}" method="DELETE">Delete</Link>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="max-w-fit mx-auto p-8">
        <h2 class="inline-block align-middle">Permissions</h2>
        <Link slideover href="{{ route('admin.newPerm') }}" class="float-right p-2 m-4 bg-green-900 text-gray-200 rounded-md">New Permission</Link>
    </div>

    <div class="max-w-fit mx-auto p-8">
        <div class="table">
            <div class="table-row">
                <div class="table-cell p-2">Permission</div>
                <div class="table-cell p-2">Actions</div>
            </div>
            @foreach(\Spatie\Permission\Models\Permission::all() as $item)
                <div class="table-row">
                    <div class="table-cell p-1 relative">
                        <x-splade-form action="{{ route('admin.permUpdate', $item->id) }}" :default="$item" submit-on-change background debounce="300">
                            <x-splade-input name="name" />
                            <x-splade-transition show="form.recentlySuccessful" animation="opacity">
                                <div class="absolute top-2 right-2 z10">&#x2705;</div>
                            </x-splade-transition>
                        </x-splade-form>
                    </div>
                    <div class="table-cell p-1">
                        <Link class="bg-red-400 p-2 rounded border" href="{{ route('admin.deletePerm', $item->id) }}" method="DELETE">Delete</Link>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>