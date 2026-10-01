<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('View as Fellow') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto p-8">
        @if($currentFellowId)
            <div class="mb-6 rounded border border-amber-400 bg-amber-50 p-4">
                <p class="font-semibold text-amber-900">
                    Currently viewing fellow pages as: {{ $currentFellowName ?? ('Fellow #' . $currentFellowId) }}
                </p>
                <div class="mt-3">
                    <Link href="{{ route('admin.clear_view_as_fellow') }}" method="POST" class="inline-block rounded bg-red-600 px-4 py-2 text-white">
                        Stop viewing as fellow
                    </Link>
                </div>
            </div>
        @endif

        <div class="rounded border bg-white p-6 shadow-sm">
            <p class="mb-4 text-sm text-gray-600">
                Select a fellow to use as context while browsing fellow pages.
            </p>

            <x-splade-form action="{{ route('admin.set_view_as_fellow') }}" method="POST" :default="['fellow_id' => $currentFellowId]">
                <div class="mb-4">
                    <x-splade-select name="fellow_id" :options="$fellows" choices />
                </div>
                <x-splade-submit label="View Fellow Pages As Selected Fellow" class="bg-green-700" />
            </x-splade-form>
        </div>

        <div class="mt-6">
            <p class="mb-2 font-semibold text-gray-700">Go to fellow pages:</p>
            <div class="flex flex-wrap gap-3">
                <Link href="{{ route('fellowsView.staff') }}" class="rounded bg-gray-200 px-4 py-2 text-gray-800">Active Staff/Students</Link>
                <Link href="{{ route('fellowsView.speedcodes') }}" class="rounded bg-gray-200 px-4 py-2 text-gray-800">My Speedcodes</Link>
                <Link href="{{ route('fellowsView.budget') }}" class="rounded bg-gray-200 px-4 py-2 text-gray-800">Budget Report</Link>
            </div>
        </div>
    </div>
</x-app-layout>
