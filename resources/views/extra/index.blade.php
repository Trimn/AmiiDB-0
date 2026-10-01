<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Data Tables') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        
        <x-splade-toggle :data="true">
            <div class="my-2">
                <button class="text-xl font-bold py-3 px-2 block w-full border rounded-t-lg text-left" @click.prevent="toggle">
                    Terms
                    <span v-show="toggled" class="float-right text-2xl">&#8212;</span>
                    <span v-show="!toggled" class="float-right text-3xl">+</span>
                </button>
                <div v-show="toggled" class="border rounded-b-lg p-2">
                    <Link slideover href="{{ route('reference.create', 'terms') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
                    <x-splade-table :for="$terms" striped />
                </div>
            </div>
        </x-splade-toggle>

        {{-- <x-splade-toggle :data="true">
            <div class="my-2">
                <button class="text-xl font-bold py-3 px-2 block w-full border rounded-t-lg text-left" @click.prevent="toggle">
                    Rates
                    <span v-show="toggled" class="float-right text-2xl">&#8212;</span>
                    <span v-show="!toggled" class="float-right text-3xl">+</span>
                </button>
                <div v-show="toggled" class="border rounded-b-lg p-2">
                    <Link slideover href="{{ route('reference.create', 'rates') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
                    <x-splade-table :for="$rates" striped />
                </div>
            </div>
        </x-splade-toggle> --}}

        <x-splade-toggle :data="true">
            <div class="my-2">
                <button class="text-xl font-bold py-3 px-2 block w-full border rounded-t-lg text-left" @click.prevent="toggle">
                    Genders
                    <span v-show="toggled" class="float-right text-2xl">&#8212;</span>
                    <span v-show="!toggled" class="float-right text-3xl">+</span>
                </button>
                <div v-show="toggled" class="border rounded-b-lg p-2">
                    <Link slideover href="{{ route('reference.create', 'genders') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
                    <x-splade-table :for="$genders" striped />
                </div>
            </div>
        </x-splade-toggle>

        <x-splade-toggle :data="true">
            <div class="my-2">
                <button class="text-xl font-bold py-3 px-2 block w-full border rounded-t-lg text-left" @click.prevent="toggle">
                    Immigration
                    <span v-show="toggled" class="float-right text-2xl">&#8212;</span>
                    <span v-show="!toggled" class="float-right text-3xl">+</span>
                </button>
                <div v-show="toggled" class="border rounded-b-lg p-2">
                    <Link slideover href="{{ route('reference.create', 'immigration') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
                    <x-splade-table :for="$immigration" striped />
                </div>
            </div>
        </x-splade-toggle>

        <x-splade-toggle :data="true">
            <div class="my-2">
                <button class="text-xl font-bold py-3 px-2 block w-full border rounded-t-lg text-left" @click.prevent="toggle">
                    Countries
                    <span v-show="toggled" class="float-right text-2xl">&#8212;</span>
                    <span v-show="!toggled" class="float-right text-3xl">+</span>
                </button>
                <div v-show="toggled" class="border rounded-b-lg p-2">
                    <Link slideover href="{{ route('reference.create', 'countries') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
                    <x-splade-table :for="$countries" striped />
                </div>
            </div>
        </x-splade-toggle>

        <x-splade-toggle :data="true">
            <div class="my-2">
                <button class="text-xl font-bold py-3 px-2 block w-full border rounded-t-lg text-left" @click.prevent="toggle">
                    Programs
                    <span v-show="toggled" class="float-right text-2xl">&#8212;</span>
                    <span v-show="!toggled" class="float-right text-3xl">+</span>
                </button>
                <div v-show="toggled" class="border rounded-b-lg p-2">
                    <Link slideover href="{{ route('reference.create', 'programs') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
                    <x-splade-table :for="$programs" striped />
                </div>
            </div>
        </x-splade-toggle>

        <x-splade-toggle :data="true">
            <div class="my-2">
                <button class="text-xl font-bold py-3 px-2 block w-full border rounded-t-lg text-left" @click.prevent="toggle">
                    Appointment Types
                    <span v-show="toggled" class="float-right text-2xl">&#8212;</span>
                    <span v-show="!toggled" class="float-right text-3xl">+</span>
                </button>
                <div v-show="toggled" class="border rounded-b-lg p-2">
                    <Link slideover href="{{ route('reference.create', 'appt_types') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
                    <x-splade-table :for="$appt_types" striped />
                </div>
            </div>
        </x-splade-toggle>

        <x-splade-toggle :data="true">
            <div class="my-2">
                <button class="text-xl font-bold py-3 px-2 block w-full border rounded-t-lg text-left" @click.prevent="toggle('staff')">
                    Staff Types
                    <span v-show="toggled" class="float-right text-2xl">&#8212;</span>
                    <span v-show="!toggled" class="float-right text-3xl">+</span>
                </button>
                <div v-show="toggled" class="border rounded-b-lg p-2">
                    <Link slideover href="{{ route('reference.create', 'staff_types') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
                    <x-splade-table :for="$staff_types" striped />
                </div>
            </div>
        </x-splade-toggle>

        <x-splade-toggle :data="true">
            <div class="my-2">
                <button class="text-xl font-bold py-3 px-2 block w-full border rounded-t-lg text-left" @click.prevent="toggle('staff_subtypes')">
                    Staff Subtypes
                    <span v-show="toggled" class="float-right text-2xl">&#8212;</span>
                    <span v-show="!toggled" class="float-right text-3xl">+</span>
                </button>
                <div v-show="toggled" class="border rounded-b-lg p-2">
                    <Link slideover href="{{ route('reference.create', 'staff_subtypes') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
                    <x-splade-table :for="$staff_subtypes" striped />
                </div>
            </div>
        </x-splade-toggle>

        <x-splade-toggle :data="true">
            <div class="my-2">
                <button class="text-xl font-bold py-3 px-2 block w-full border rounded-t-lg text-left" @click.prevent="toggle('status')">
                    Statuses
                    <span v-show="toggled" class="float-right text-2xl">&#8212;</span>
                    <span v-show="!toggled" class="float-right text-3xl">+</span>
                </button>
                <div v-show="toggled" class="border rounded-b-lg p-2">
                    <Link slideover href="{{ route('reference.create', 'status') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
                    <x-splade-table :for="$status" striped />
                </div>
            </div>
        </x-splade-toggle>

        <x-splade-toggle :data="true">
            <div class="my-2">
                <button class="text-xl font-bold py-3 px-2 block w-full border rounded-t-lg text-left" @click.prevent="toggle('finance_team')">
                    Assignable to Projects
                    <span v-show="toggled" class="float-right text-2xl">&#8212;</span>
                    <span v-show="!toggled" class="float-right text-3xl">+</span>
                </button>
                <div v-show="toggled" class="border rounded-b-lg p-2">
                    <Link slideover href="{{ route('reference.create', 'finance_team') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
                    <x-splade-table :for="$finance_team" striped />
                </div>
            </div>
        </x-splade-toggle>

        <x-splade-toggle :data="true">
            <div class="my-2">
                <button class="text-xl font-bold py-3 px-2 block w-full border rounded-t-lg text-left" @click.prevent="toggle('project_team')">
                    Project Team
                    <span v-show="toggled" class="float-right text-2xl">&#8212;</span>
                    <span v-show="!toggled" class="float-right text-3xl">+</span>
                </button>
                <div v-show="toggled" class="border rounded-b-lg p-2">
                    <Link slideover href="{{ route('reference.create', 'project_team') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
                    <x-splade-table :for="$project_team" striped />
                </div>
            </div>
        </x-splade-toggle>

        <x-splade-toggle :data="true">
            <div class="my-2">
                <button class="text-xl font-bold py-3 px-2 block w-full border rounded-t-lg text-left" @click.prevent="toggle('project_priority')">
                    Project Priorities
                    <span v-show="toggled" class="float-right text-2xl">&#8212;</span>
                    <span v-show="!toggled" class="float-right text-3xl">+</span>
                </button>
                <div v-show="toggled" class="border rounded-b-lg p-2">
                    <Link slideover href="{{ route('reference.create', 'project_priority') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
                    <x-splade-table :for="$project_priority" striped />
                </div>
            </div>
        </x-splade-toggle>

        <x-splade-toggle :data="true">
            <div class="my-2">
                <button class="text-xl font-bold py-3 px-2 block w-full border rounded-t-lg text-left" @click.prevent="toggle('project_evaluation')">
                    Project Evaluation Statuses
                    <span v-show="toggled" class="float-right text-2xl">&#8212;</span>
                    <span v-show="!toggled" class="float-right text-3xl">+</span>
                </button>
                <div v-show="toggled" class="border rounded-b-lg p-2">
                    <Link slideover href="{{ route('reference.create', 'project_evaluation') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
                    <x-splade-table :for="$project_evaluation" striped />
                </div>
            </div>
        </x-splade-toggle>
    </div>
</x-app-layout>
