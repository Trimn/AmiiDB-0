@seoTitle("$person->first_name $person->last_name")
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ $person->first_name }} {{ $person->last_name }}
        </h2>
    </x-slot>
    
    <div class="w-full p-8 flex flex-auto">
        <div class="w-96 p-8 mx-2 flex-none">
            @if($fellow)
            <img v-show="{{ !is_null($fellow->photo) }}" src="{{ \Illuminate\Support\Facades\Storage::url($fellow->photo) }}" class="max-w-xs max-h-72 mx-auto" />
            <x-splade-form :for="$fellowForm" />
            @else
            <x-splade-form :default="$person" :action="route('people.update', $person)">
                <div class="columns-2">
                    <x-splade-input name="last_name" label="Last Name" :readonly="!$edit" />
                    <x-splade-input name="first_name" label="First Name" :readonly="!$edit" />
                </div>
                <x-splade-input name="email" label="Alternate Email" :readonly="!$edit" />
                <x-splade-input name="uid" label="University ID" readonly />
                <x-splade-input name="ccid" label="CCID" :readonly="!$edit" />
                <x-splade-select name="gender" :options="$genders" label="Gender" :disabled="!$edit" />
                <div class="columns-2">
                    <x-splade-select name="citizenship" :options="\App\Models\Countries::options()" label="Citizenship" :disabled="!$edit" />
                    <x-splade-select name="immigration" :options="$immigrations" label="Immigration" choices :disabled="!$edit" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <x-splade-input name="amii_start" label="Amii Start Date" date :readonly="!$edit"  />
                    <x-splade-input name="amii_end" label="Amii End Date" date :readonly="!$edit" />
                </div>
                <x-splade-select name="wp_type" label="Permit type" :options="\App\Models\People::wp_types()" choices :disabled="!$edit" />
                <div class="grid grid-cols-2 gap-4">
                    <x-splade-input name="wp_start" label="Permit Start" date :readonly="!$edit" />
                    <x-splade-input name="wp_end" label="Permit End" date :readonly="!$edit" />
                </div>
                @if(!$fellow)
                <x-splade-select name="supervisor" :options="$fellows" label="Primary Supervisor" choices :disabled="!$edit" />
                <x-splade-input name="supervisor2" label="Secondary Supervisor" :readonly="!$edit" />
                <x-splade-input name="post_uofa_employer" label="Post UofA Employer" :readonly="!$edit" />
                @if(!is_null($person->post_uofa_employer_updated_at))
                    <span class="inline-block text-gray-700 text-sm italic">Last modified: {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $person->post_uofa_employer_updated_at, 'UTC')->setTimezone('America/Edmonton')->format('M j, Y g:i A') }} MT</span>
                @else
                    <span class="inline-block text-gray-700 text-sm italic">Last modified: never</span>
                @endif
                @endif
                @if($edit)
                    <x-splade-submit class="my-2" label="Update" class="bg-gray-700 text-white" :readonly="!$edit" />
                @endif
            </x-splade-form>
            @if($edit)
                <Link slideover href="{{ route('students.new_record', $person) }}" class="inline-block p-2 mt-2 mx-auto font-bold bg-amber-700 text-white rounded-md">New Student Program</Link><br />
                <Link slideover href="{{ route('staff.new_record', $person) }}" class="inline-block p-2 mt-2 mx-auto font-bold bg-indigo-500 text-white rounded-md">New Staff Job</Link>
            @endif
            @endif
            <span class="inline-block text-gray-700 text-sm italic">Last updated at: {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $person->updated_at, 'UTC')->setTimezone('America/Edmonton') }} MT</span>
        </div>
        <div class="w-3/5 p-8 mx-2 flex-auto float-right">
            @foreach($students as $student)
                @if($student instanceof \App\Models\Student)
                <x-splade-toggle data="showStudent">
                    <div>
                        <h2 class="p-3 font-bold text-xl border" @click.prevent="toggle('showStudent')">
                            Student
                            <span v-show="showStudent" class="float-right">+</span>
                            <span v-show="!showStudent" class="float-right">-</span>
                        </h2>
                    </div>
                    <div v-show="!showStudent" class="border p-3">
                        <x-splade-form :default="$student" :action="route('student.update', $student)">
                            <div class="flex gap-4">
                                <x-splade-select name="program" :options="$programs" label="Program" :disabled="!$edit" class="flex-1" />
                                <x-splade-select name="program_start" :options="$terms" label="Program Start" :disabled="!$edit" class="flex-1" />
                                <x-splade-input name="convocation" label="Final Exam Pass Date" date :readonly="!$edit" class="flex-1" />
                            </div>
                            <div v-show="form.program == 'PhD'">
                                <x-splade-checkbox name="phd_post" label="Ph.D. Post" :readonly="!$edit" />
                                @if(!is_null($student->phd_post_checked))
                                    <span class="inline-block text-gray-700 text-sm italic">Checked on: {{ $student->phd_post_checked }}</span>
                                @endif
                            </div>
                            <div class="columns-3">
                                <x-splade-input name="dept" label="Dept" :readonly="!$edit" />
                                <x-splade-select name="active" :options="$statuses" label="Status" :disabled="!$edit" />
                                <x-splade-input name="curr_step" label="Current Salary Step" :readonly="!$edit" />
                            </div>
                            <div class="columns-2">
                                <x-splade-input label="Term Adjust" name="term_adj" :readonly="!$edit" />
                                <x-splade-select label="GF Last" name="gf_last" :options="\App\Models\Terms::options()" :disabled="!$edit" />
                            </div>
                            
                            <x-splade-textarea name="notes" label="Notes" autosize :readonly="!$edit" />
                            @if($edit)
                            <x-splade-submit class="my-2" label="Update" class="bg-amber-700 text-white" />
                            @endif
                        </x-splade-form>

                        <h2 class="p-3 font-bold text-lg" @click.prevent="setToggle('showAppt')">Appointments</h2>
                        @if($edit)
                        <Link slideover href="{{ route('student.appt.new', $student) }}" class="float-right p-2 bg-amber-700 text-white rounded-md">New Appointment</Link>
                        @endif
                        <x-splade-table :for="new \App\Tables\StudentApptTable($student)">
                            <x-splade-cell delete>
                                <Link class="p-2 rounded bg-red-500 text-gray-200" method="GET" href="/student/appointment/delete/{{ $item->id }}">Delete</Link>
                            </x-splade-cell>
                        </x-splade-table>
                    </div>
                </x-splade-toggle>
                @endif
            @endforeach

            @foreach ($staffs as $staff)
                @if($staff instanceof \App\Models\Staff)
                <x-splade-toggle>
                    <div>
                        <h2 class="p-3 font-bold text-xl border" @click.prevent="toggle">
                            Staff
                            <span v-show="toggled" class="float-right">+</span>
                            <span v-show="!toggled" class="float-right">-</span>
                        </h2>
                        <div class="border" v-show="!toggled">
                            <x-splade-form :default="$staff" :action="route('staff.update', $staff)">
                                <div class="columns-2">
                                    <x-splade-input name="job_title" label="Job Title" :readonly="!$edit" />
                                    <x-splade-input name="dept" label="Department" :readonly="!$edit" />
                                </div>
                                @php
                                    $pdfSubtypeIds = [];
                                    foreach ($subtypes as $id => $name) {
                                        if (stripos($name, 'pd') !== false) {
                                            $pdfSubtypeIds[] = $id;
                                        }
                                    }
                                    $isPdfSubtype = $staff->pos_subtype && stripos($staff->pos_subtype->name, 'pd') !== false;
                                @endphp
                                <div class="flex gap-3">
                                    <x-splade-select name="pos_type" label="Position Type" :options="$posTypes" :disabled="!$edit" class="flex-1" />
                                    <x-splade-select name="subtype" label="Subtype" :options="$subtypes" :disabled="!$edit" class="flex-1" />
                                    <div v-show="[{{ implode(',', $pdfSubtypeIds) }}].includes(Number(form.subtype))" class="flex-1">
                                        <x-splade-input name="pdf_completed" label="PDS Completed" date :readonly="!$edit" />
                                    </div>
                                </div>
                                <x-splade-select name="active" label="Status" :options="$statuses" :disabled="!$edit" />
                                <x-splade-textarea name="notes" label="Notes" autosize :readonly="!$edit" />
                                @if($edit)
                                    <x-splade-submit class="my-2" label="Update" />
                                @endif
                            </x-splade-form>

                            <h2 class="p-3 font-bold text-lg">Appointments</h2>
                            @if($edit)
                                <Link slideover href="{{ route('staff.appt.create', $staff) }}" class="float-right p-2 bg-indigo-500 text-white rounded-md">New Appointment</Link>
                            @endif
                            <x-splade-table :for="new \App\Tables\StaffApptTable($staff)">
                                <x-splade-cell delete>
                                    <Link class="p-2 rounded bg-red-500 text-gray-200" method="DELETE" href="/staff/appt/{{ $item->id }}">Delete</Link>
                                </x-splade-cell>
                            </x-splade-table>
                        </div>
                    </div>
                </x-splade-toggle>
                @endif
            @endforeach

            @if(!$fellow)
                <x-splade-toggle>
                    <div>
                        <h2 class="p-3 font-bold text-xl border" @click.prevent="toggle">
                            Awards
                            <span v-show="toggled" class="float-right">+</span>
                            <span v-show="!toggled" class="float-right">-</span>
                        </h2>
                        <div class="border" v-show="!toggled">
                            @if($edit)
                                <Link slideover href="{{ route('awards.create', $person) }}" class="float-right p-2 bg-purple-700 text-white rounded-md">New Award</Link>
                            @endif
                            <x-splade-table :for="new \App\Tables\PersonAwardTable($person)" />
                        </div>
                    </div>
                </x-splade-toggle>

                <x-splade-toggle>
                    <div>
                        <h2 class="p-3 font-bold text-xl border" @click.prevent="toggle">
                            Payments
                            <span v-show="toggled" class="float-right">+</span>
                            <span v-show="!toggled" class="float-right">-</span>
                        </h2>
                        <div class="border" v-show="!toggled">
                            @if($edit)
                                <Link slideover href="{{ route('payments.create', $person) }}" class="float-right p-2 bg-green-500 text-white rounded-md">New Payment</Link>
                            @endif
                            <x-splade-table :for="new \App\Tables\PaymentsTable($person)" />
                        </div>
                    </div>
                </x-splade-toggle>

                <x-splade-toggle>
                    <div>
                        <h2 class="p-3 font-bold text-xl border" @click.prevent="toggle">
                            Budgets
                            <span v-show="toggled" class="float-right">+</span>
                            <span v-show="!toggled" class="float-right">-</span>
                        </h2>
                        <div class="border" v-show="!toggled">
                            @if($edit)
                                <Link slideover href="{{ route('budgets.create.person', $person) }}" class="float-right p-2 bg-blue-500 text-white rounded-md">New Budget</Link>
                            @endif
                            <x-splade-table :for="new \App\Tables\PersonBudgetTable($person)" />
                        </div>
                    </div>
                </x-splade-toggle>
            @endif

            @if($fellow)
                <h2 class="p-3 font-bold text-xl">Students</h2>
                <x-splade-table :for="$fellowStudents" />

                <h2 class="p-3 font-bold text-xl">Staff</h2>
                <x-splade-table :for="$fellowStaff" />
            @endif

            {{-- <h2>Visitor</h2>
            <x-splade-form :for="$visitor" />

            <h2>Fellow</h2>
            <x-splade-form :for="$fellow" /> --}}
        </div>
    </div>
</x-app-layout>