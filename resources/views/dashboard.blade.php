<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @foreach($categories as $category)
            <div class="py-10">
                <p class="mx-auto text-center max-w-6xl {{ !is_null($category->colour) ? $category->colour : 'text-green-800' }} font-extrabold text-2xl uppercase">{{ $category->category }}</p>
                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    <div class="flex flex-wrap justify-center">
            @foreach($category->children as $child)
                        <Link href="{{ !is_null($child->route) ? route($child->route) : "#" }}" class="inline-block bg-gray-100 px-20 py-5 align-middle content-center border-2 rounded-lg m-4 hover:border-blue-500">
                            {{ $child->name }}
                        </Link>
            @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
