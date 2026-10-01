<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Create New User
        </h2>
    </x-slot>

    <x-splade-modal>
        <x-splade-form action="{{ route('admin.updatepassword', $user) }}" class="space-y-4 px-10" @success="$splade.emit('user-changed')">
            <x-splade-input id="password" type="password" name="password" :label="__('Password')" required autocomplete="new-password" />
            <x-splade-input id="password_confirmation" type="password" name="password_confirmation" :label="__('Confirm Password')" required />

            <div class="flex items-center justify-end">
                <x-splade-submit class="ml-4" :label="__('Register')" />
            </div>
        </x-splade-form>
    </x-splade-modal>
</x-app-layout>