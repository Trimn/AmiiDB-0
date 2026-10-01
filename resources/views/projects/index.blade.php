<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="max-w-fit mx-auto p-8">
        <Link slideover href="{{ route('projects.create') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
        <x-splade-rehydrate on="projects-updated">
            <x-splade-table :for="$table" pagination-scroll="preserve" striped>
                <x-splade-cell days-left>
                    @php $val = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($item->speedcode['award_end']), false) @endphp
                    @if($val < 0)
                        <div class="bg-red-400 text-black px-2 py-1 rounded-md">
                            {{ $val }}
                        </div>
                    @else
                        {{ $val }}
                    @endif
                </x-splade-cell>
                <x-splade-cell project_view.funds_before_calc>
                    @php $val = $item->get_funds_before() @endphp
                    @if($val < 0)
                        <div class="bg-red-400 text-black px-2 py-1 rounded-md">
                            {{ is_null($val) ? $val : Number::currency($val) }}
                        </div>
                    @else
                        {{ is_null($val) ? $val : Number::currency($val) }}
                    @endif
                </x-splade-cell>
                <x-splade-cell project_view.funds_after_calc>
                    @php $val = $item->get_funds_after() @endphp
                    @if($val < 0)
                        <div class="bg-red-400 text-black px-2 py-1 rounded-md">
                            {{ is_null($val) ? $val : Number::currency($val) }}
                        </div>
                    @else
                        {{ is_null($val) ? $val : Number::currency($val) }}
                    @endif
                </x-splade-cell>
                <x-splade-cell ff_verified>
                    <x-splade-form default="{{ $item }}" action="{{ route('projects.checkboxUpdate', $item) }}" method="POST" submit-on-change background>
                        <x-splade-checkbox @click.stop="" name="ff_verified" />
                    </x-splade-form>
                </x-splade-cell>
                <x-splade-cell priority.priority>
                    <div class="{{ \App\Models\ProjectPriority::getBgColour($item->priority) }} {{ \App\Models\ProjectPriority::getTextColour($item->priority) }} p-1 rounded">
                        {{ is_null($item->priority) ? '' : $item->priority->priority }}
                    </div>
                </x-splade-cell>
                <x-splade-cell eval_status.status>
                    <div class="{{ \App\Models\ProjectEvaluation::getBgColour($item->eval_status) }} {{ \App\Models\ProjectEvaluation::getTextColour($item->eval_status) }} p-1 rounded">
                        {{ is_null($item->eval_status) ? '' : $item->eval_status->status }}
                    </div>
                </x-splade-cell>
                <x-splade-cell financial_report>
                    <x-splade-form default="{{ $item }}" action="{{ route('projects.checkboxUpdate', $item) }}" method="POST" submit-on-change background>
                        <x-splade-checkbox @click.stop="" name="financial_report" />
                    </x-splade-form>
                </x-splade-cell>
                <x-splade-cell supervisor_review>
                    <x-splade-form default="{{ $item }}" action="{{ route('projects.checkboxUpdate', $item) }}" method="POST" submit-on-change background>
                        <x-splade-checkbox @click.stop="" name="supervisor_review" />
                    </x-splade-form>
                </x-splade-cell>
                <x-splade-cell notes>
                    <div class="w-[20rem] max-h-[8rem] overflow-y-scroll text-wrap">
                        {!! nl2br(e($item->notes)) !!}
                    </div>
                </x-splade-cell>
            </x-splade-table>
        </x-splade-rehydrate>
    </div>
@if(Illuminate\Support\Facades\Auth::user()->can('view'))
    <div class="mx-w-fit mx-auto p-8">
        <h1 class="text-2xl font-bold p-2">Upload Researcher Homepage export</h1>
        <x-splade-form :action="route('projects.import')">
            <div class="columns-2">
                <x-splade-file name="file" filepond accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/csv,text/html" />
                <x-splade-submit @click="(e) => {
                    const formEl = e?.target?.closest('form');
                    const filepondRoot = formEl?.querySelector('.filepond--root');
                    if (!filepondRoot) return;

                    const hasFiles = filepondRoot.querySelectorAll('.filepond--item').length > 0;
                    if (!hasFiles) {
                        e.preventDefault();
                        e.stopPropagation();
                        const browser = formEl.querySelector('input[type=&quot;file&quot;].filepond--browser') || filepondRoot.querySelector('input[type=&quot;file&quot;]');
                        browser?.click();
                    }
                }">Upload</x-splade-submit>
            </div>
            <span class="inline-block text-gray-700 italic text-sm">{{ \App\Models\ProjectImports::lastUploadDate() }}</span>
        </x-splade-form>

        <h1 class="text-2xl font-bold p-2">Upload Expenditures export</h1>
        <x-splade-form :action="route('projects.importEtrac')">
            <div class="columns-2">
                <x-splade-file name="file" filepond accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/csv,text/html" />
                <x-splade-submit @click="(e) => {
                    const formEl = e?.target?.closest('form');
                    const filepondRoot = formEl?.querySelector('.filepond--root');
                    if (!filepondRoot) return;

                    const hasFiles = filepondRoot.querySelectorAll('.filepond--item').length > 0;
                    if (!hasFiles) {
                        e.preventDefault();
                        e.stopPropagation();
                        const browser = formEl.querySelector('input[type=&quot;file&quot;].filepond--browser') || filepondRoot.querySelector('input[type=&quot;file&quot;]');
                        browser?.click();
                    }
                }">Upload</x-splade-submit>
            </div>
            <span class="inline-block text-gray-700 italic text-sm">{{ \App\Models\EtracImport::lastUploadDate() }}</span>
        </x-splade-form>

        <h1 class="text-2xl font-bold p-2">Upload Salary export</h1>
        <x-splade-form :action="route('projects.importSalary')">
            <div class="columns-2">
                <x-splade-file name="file" filepond accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/csv,text/html" />
                <x-splade-submit @click="(e) => {
                    const formEl = e?.target?.closest('form');
                    const filepondRoot = formEl?.querySelector('.filepond--root');
                    if (!filepondRoot) return;

                    const hasFiles = filepondRoot.querySelectorAll('.filepond--item').length > 0;
                    if (!hasFiles) {
                        e.preventDefault();
                        e.stopPropagation();
                        const browser = formEl.querySelector('input[type=&quot;file&quot;].filepond--browser') || filepondRoot.querySelector('input[type=&quot;file&quot;]');
                        browser?.click();
                    }
                }">Upload</x-splade-submit>
            </div>
            <span class="inline-block text-gray-700 italic text-sm">{{ \App\Models\SalaryImport::lastUploadDate() }}</span>
        </x-splade-form>

        @if(!is_null(\App\Models\NewProjects::first()))
        <h1 class="text-2xl font-bold p-2">New Speedcodes from Previous RHP Import</h1>
        <x-splade-table :for="\App\Tables\NewProjectsTable::class" striped>
            <x-splade-cell actions>
                <Link href="{{ route('projects.add', $item->id) }}" class="float-right p-2 mx-2 bg-green-900 text-gray-200 rounded-md" preserve-scroll>Add</Link> 
                <Link href="{{ route('projects.ignore', $item->id) }}" class="float-right p-2 bg-red-900 text-gray-200 rounded-md" preserve-scroll>Ignore</Link>
            </x-splade-cell>
        </x-splade-table>
        @endif

        @if(!is_null(\App\Models\MissingProjects::first()))
        <h1 class="text-2xl font-bold p-2">Missing Speedcodes from Previous RHP Import</h1>
        <x-splade-table :for="\App\Tables\MissingProjectsTable::class" striped>
            <x-splade-cell actions>
                <Link href="{{ route('projects.complete', $item->id) }}" class="float-right p-2 mx-2 bg-green-900 text-gray-200 rounded-md" preserve-scroll>Complete</Link> 
                <Link href="{{ route('projects.ignoreMissing', $item->id) }}" class="float-right p-2 bg-red-900 text-gray-200 rounded-md" preserve-scroll>Ignore</Link>
            </x-splade-cell>
        </x-splade-table>
        @endif

        <x-splade-lazy>
            <h1 class="text-2xl font-bold p-2">Current Expenditures data</h1>
            <x-splade-table :for="\App\Tables\EtracTable::class" striped />
        </x-splade-lazy>
    </div>
@endif

</x-app-layout>