@if(!empty($viewAsFellowName))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="rounded border border-amber-400 bg-amber-50 p-3 text-sm text-amber-900">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    Viewing as fellow: <span class="font-semibold">{{ $viewAsFellowName }}</span>
                </div>

                <x-splade-form
                    action="{{ route('admin.set_view_as_fellow') }}"
                    method="POST"
                    :default="['fellow_id' => $viewAsFellowId]"
                    class="flex items-center gap-2"
                >
                    <x-splade-select
                        name="fellow_id"
                        :options="$viewAsFellowOptions"
                        choices
                        class="min-w-[16rem]"
                    />
                    <x-splade-submit class="bg-amber-700 text-white">
                        Switch
                    </x-splade-submit>
                </x-splade-form>

                <Link href="{{ route('admin.view_as_fellow') }}" class="inline-flex items-center rounded bg-gray-700 px-3 py-2 text-white">
                    Back to View As Menu
                </Link>
            </div>
        </div>
    </div>
@endif
