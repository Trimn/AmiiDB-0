<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Navigation') }}
        </h2>
    </x-slot>

    <div class="max-w-fit mx-auto p-8">
        <h2>Navigation Items</h2>
    </div>
    <div class="max-w-fit mx-auto p-8">
        <Link slideover href="{{ route('admin.nav_create') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
        <div class="table">
            <div class="table-row">
                <div class="table-cell uppercase text-sm p-2 border">Name</div>
                <div class="table-cell uppercase text-sm p-2 border">Route</div>
                <div class="table-cell uppercase text-sm p-2 border">Category</div>
                <div class="table-cell uppercase text-sm p-2 border">Order</div>
                <div class="table-cell uppercase text-sm p-2 border">Permission</div>
                <div class="table-cell uppercase text-sm p-2 border">Actions</div>
            </div>
            @foreach($nav as $item)
                <div class="table-row even:bg-gray-100 odd:bg-gray-200 hover:bg-gray-300">
                    <div class="table-cell border p-1 relative">
                        <x-splade-form action="{{ route('admin.nav_edit', $item->id) }}" :default="$item" submit-on-change background debounce="500">
                            <x-splade-input name="name" />
                            <x-splade-transition show="form.recentlySuccessful" animation="opacity">
                                <div class="absolute top-2 right-2 z10">&#x2705;</div>
                            </x-splade-transition>
                        </x-splade-form>
                    </div>
                    <div class="table-cell border p-1 relative">
                        <x-splade-form action="{{ route('admin.nav_edit', $item->id) }}" :default="$item" submit-on-change background debounce="500">
                            <x-splade-select name="route" :options="$routes" choices />
                            <x-splade-transition show="form.recentlySuccessful" animation="opacity">
                                <div class="absolute top-2 right-2 z10">&#x2705;</div>
                            </x-splade-transition>
                        </x-splade-form>
                    </div>
                    <div class="table-cell border p-1 relative">
                        <x-splade-form action="{{ route('admin.nav_edit', $item->id) }}" :default="$item" submit-on-change background debounce="500">
                            <x-splade-select name="category" :options="\App\Models\NavCategories::options()" choices />
                            <x-splade-transition show="form.recentlySuccessful" animation="opacity">
                                <div class="absolute top-2 right-2 z10">&#x2705;</div>
                            </x-splade-transition>
                        </x-splade-form>
                    </div>
                    <div class="table-cell border p-1 relative">
                        <x-splade-form action="{{ route('admin.nav_edit', $item->id) }}" :default="$item" submit-on-change background debounce="500">
                            <x-splade-input name="order" />
                            <x-splade-transition show="form.recentlySuccessful" animation="opacity">
                                <div class="absolute top-2 right-2 z10">&#x2705;</div>
                            </x-splade-transition>
                        </x-splade-form>
                    </div>
                    <div class="table-cell border p-1 mx-4 relative">
                        <x-splade-form action="{{ route('admin.nav_edit', $item->id) }}" :default="$item" submit-on-change background debounce="500">
                            <x-splade-select name="permission" :options="\Spatie\Permission\Models\Permission::all()->pluck('name', 'name')" choices />
                            <x-splade-transition show="form.recentlySuccessful" animation="opacity">
                                <div class="absolute top-2 right-2 z10">&#x2705;</div>
                            </x-splade-transition>
                        </x-splade-form>
                    </div>
                    <div class="table-cell border p-1 mx-4 relative">
                        <Link class="bg-red-400 p-2 rounded border" method="DELETE" href="{{ route('admin.nav_delete', $item->id) }}">Delete</Link>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="max-w-fit mx-auto p-8">
        <h2>Navigation Categories</h2>
    </div>
    <div class="max-w-fit mx-auto p-8">
        <div class="table">
            <div class="table-row">
                <div class="table-cell">Name</div>
                <div class="table-cell">Order</div>
                <div class="table-cell">Colour</div>
                <div class="table-cell">Example</div>
            </div>
            @php
                $data = \App\Models\NavCategories::all();
            @endphp
            @foreach($data as $item)
                <div class="table-row">
                        <div class="table-cell p-1 relative">
                            <x-splade-form action="{{ route('admin.cat_edit', $item->category) }}" :default="$item" submit-on-change background debounce="500">
                                <x-splade-input name="category" />
                                <x-splade-transition show="form.recentlySuccessful" animation="opacity">
                                    <div class="absolute top-2 right-2 z10">&#x2705;</div>
                                </x-splade-transition>
                            </x-splade-form>
                        </div>
                        <div class="table-cell p-1 relative">
                            <x-splade-form action="{{ route('admin.cat_edit', $item->category) }}" :default="$item" submit-on-change background debounce="500">
                                <x-splade-input name="order" />
                                <x-splade-transition show="form.recentlySuccessful" animation="opacity">
                                    <div class="absolute top-2 right-2 z10">&#x2705;</div>
                                </x-splade-transition>
                            </x-splade-form>
                        </div>
                        <div class="table-cell p-1 relative">
                            <x-splade-form action="{{ route('admin.cat_edit', $item->category) }}" :default="$item" submit-on-change background debounce="500">
                                <x-splade-input name="colour" />
                                <x-splade-transition show="form.recentlySuccessful" animation="opacity">
                                    <div class="absolute top-2 right-2 z10">&#x2705;</div>
                                </x-splade-transition>
                            </x-splade-form>
                        </div>
                        <div class="table-cell p-1"><div class="font-bold {{ !is_null($item->colour) ? $item->colour : 'text-green-500' }}">{{ $item->category }}</div></div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>