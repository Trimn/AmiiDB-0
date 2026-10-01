<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Missing Salary People') }}
            </h2>
            @if($showIgnored)
                <a href="{{ route('people.missing_salary') }}" class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700">
                    Show Active
                </a>
            @else
                <a href="{{ route('people.missing_salary', ['show_ignored' => 1]) }}" class="px-4 py-2 bg-yellow-600 text-white text-sm rounded-md hover:bg-yellow-700">
                    Show Ignored
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-splade-table :for="$table" striped>
                        <x-splade-cell actions>
                            <div class="flex space-x-2">
                                @if(!$item->ignored)
                                    <Link modal @click.stop="" href="{{ route('people.missing_salary_add_form', ['uid' => $item->uid, 'name' => $item->name]) }}">
                                        <button class="p-2 bg-green-900 text-gray-200 rounded-md">Add</button>
                                    </Link>
                                    <x-splade-form :default="['uid' => $item->uid]" action="{{ route('people.missing_salary_ignore') }}" method="POST">
                                        <button @click.stop="" type="submit" class="p-2 bg-red-900 text-gray-200 rounded-md">Ignore</button>
                                    </x-splade-form>
                                @else
                                    <x-splade-form :default="['uid' => $item->uid]" action="{{ route('people.missing_salary_unignore') }}" method="POST">
                                        <button @click.stop="" type="submit" class="p-2 bg-blue-700 text-gray-200 rounded-md">Unignore</button>
                                    </x-splade-form>
                                @endif
                            </div>
                        </x-splade-cell>
                    </x-splade-table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
