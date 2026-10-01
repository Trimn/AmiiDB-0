<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Admin Options') }}
        </h2>
    </x-slot>

    <div class="max-w-fit mx-auto p-8">
        <Link slideover href="{{ route('admin.newuser') }}" class="p-8 m-4 bg-green-900 text-gray-200 rounded-md">New User</Link>
        <Link href="{{ route('admin.navigation') }}" class="p-8 m-4 bg-gray-200 text-gray-900 rounded-md">Edit Navigation</Link>
        <Link href="{{ route('admin.perms') }}" class="p-8 m-4 bg-gray-200 text-gray-900 rounded-md">Edit Permissions</Link>
    </div>

    <x-splade-rehydrate on="user-changed">
        <div class="max-w-fit mx-auto p-8">
            <div class="table">
                <div class="table-row">
                    <div class="table-cell">Username</div>
                    <div class="table-cell">Email</div>
                    <div class="table-cell">Roles</div>
                    <div class="table-cell">Actions</div>
                </div>
                @php
                    $data = \App\Models\User::all();
                @endphp
                @foreach($data as $user)
                    <div class="table-row">
                        <div class="table-cell p-1">{{ $user->name }}</div>
                        <div class="table-cell p-1">{{ $user->email }}</div>
                        <div class="table-cell p-1">
                            <div class="relative">
                                <x-splade-form action="{{ route('admin.changerole', $user->id) }}" :default="$user" method="POST" submit-on-change background debounce="300">
                                    <x-splade-select name="roles" :options="\Spatie\Permission\Models\Role::all()->pluck('name', 'id')" relation choices />
                                    <x-splade-transition show="form.recentlySuccessful" animation="opacity">
                                        <div class="absolute right-2 top-2 z10">&#x2705;</div>
                                    </x-splade-transition>
                                </x-splade-form>
                            </div>
                        </div>
                        <div class="table-cell p-1">
                            <Link slideover class="bg-blue-400 p-2 rounded border" href="{{ route('admin.resetpassword', $user) }}" method="GET">Reset Password</Link>
                            <Link class="bg-red-400 p-2 rounded border" href="{{ route('admin.deleteuser', $user->id) }}" method="DELETE">Delete</Link>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-splade-rehydrate>

</x-app-layout>