<?php

namespace App\Tables;

use Carbon\Carbon;
use App\Models\Staff;
use App\Models\People;
use App\Models\Student;
use App\Models\StaffAppt;
use App\Models\StudentAppt;
use Illuminate\Http\Request;
use App\Models\AffiliateStaff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;

class ApptMetrics extends AbstractTable
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return Auth::check();
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        $start = request()->query('start', Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now()->subYear(1)->year . '-03-01 00:00:01')->format('Y-m-d'));
        $end = request()->query('end', Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now()->year . '-03-01 00:00:00')->subSeconds(2)->format('Y-m-d'));
        
        $query = Student::query()
            ->select([
                'people.last_name',
                'people.first_name', 
                'people.uid',
                DB::raw('program AS type'),
                DB::raw('student.convocation AS convocation'),
                DB::raw('NULL AS pdf_completed'),
                'people.amii_start',
                'people.amii_end',
                DB::raw('CONCAT(supervisor_person.first_name, " ", supervisor_person.last_name) as supervisor_name'),
                'people.supervisor2'
            ])
            ->leftJoin('people', 'student.pid', '=', 'people.id')
            ->leftJoin('fellows', 'people.supervisor', '=', 'fellows.id')
            ->leftJoin('people as supervisor_person', 'fellows.pid', '=', 'supervisor_person.id')
            ->whereIn('student.id', StudentAppt::select('sid')->where('start', '<=', $end)->where('end', '>=', $start));
        
        $ugrad = Staff::query()
            ->select([
                'people.last_name',
                'people.first_name',
                'people.uid',
                DB::raw('"Undergrad" AS type'),
                DB::raw('NULL AS convocation'),
                DB::raw('staff.pdf_completed AS pdf_completed'),
                'people.amii_start',
                'people.amii_end',
                DB::raw('CONCAT(supervisor_person.first_name, " ", supervisor_person.last_name) as supervisor_name'),
                'people.supervisor2'
            ])
            ->leftJoin('people', 'staff.pid', '=', 'people.id')
            ->leftJoin('fellows', 'people.supervisor', '=', 'fellows.id')
            ->leftJoin('people as supervisor_person', 'fellows.pid', '=', 'supervisor_person.id')
            ->leftJoin('staff_types', 'staff.pos_type', '=', 'staff_types.id')
            ->leftJoin('staff_subtypes', 'staff.subtype', '=', 'staff_subtypes.id')
            ->whereIn('staff.id', StaffAppt::select('staff_id')->where('start', '<=', $end)->where('end', '>=', $start))
            ->where('staff_types.name', 'LIKE', '%undergrad%');

        $pdf = Staff::query()
            ->select([
                'people.last_name',
                'people.first_name',
                'people.uid',
                DB::raw('"PDS" AS type'),
                DB::raw('NULL AS convocation'),
                DB::raw('staff.pdf_completed AS pdf_completed'),
                'people.amii_start',
                'people.amii_end',
                DB::raw('CONCAT(supervisor_person.first_name, " ", supervisor_person.last_name) as supervisor_name'),
                'people.supervisor2'
            ])
            ->leftJoin('people', 'staff.pid', '=', 'people.id')
            ->leftJoin('fellows', 'people.supervisor', '=', 'fellows.id')
            ->leftJoin('people as supervisor_person', 'fellows.pid', '=', 'supervisor_person.id')
            ->leftJoin('staff_types', 'staff.pos_type', '=', 'staff_types.id')
            ->leftJoin('staff_subtypes', 'staff.subtype', '=', 'staff_subtypes.id')
            ->whereIn('staff.id', StaffAppt::select('staff_id')->where('start', '<=', $end)->where('end', '>=', $start))
            ->where('staff_subtypes.name', 'LIKE', '%pd%');

        $other = Staff::query()
            ->select([
                'people.last_name',
                'people.first_name',
                'people.uid',
                DB::raw('"Other" AS type'),
                DB::raw('NULL AS convocation'),
                DB::raw('staff.pdf_completed AS pdf_completed'),
                'people.amii_start',
                'people.amii_end',
                DB::raw('CONCAT(supervisor_person.first_name, " ", supervisor_person.last_name) as supervisor_name'),
                'people.supervisor2'
            ])
            ->leftJoin('people', 'staff.pid', '=', 'people.id')
            ->leftJoin('fellows', 'people.supervisor', '=', 'fellows.id')
            ->leftJoin('people as supervisor_person', 'fellows.pid', '=', 'supervisor_person.id')
            ->leftJoin('staff_types', 'staff.pos_type', '=', 'staff_types.id')
            ->leftJoin('staff_subtypes', 'staff.subtype', '=', 'staff_subtypes.id')
            ->whereIn('staff.id', StaffAppt::select('staff_id')->where('start', '<=', $end)->where('end', '>=', $start))
            ->where('staff_subtypes.name', 'NOT LIKE', '%pd%')
            ->where('staff_types.name', 'NOT LIKE', '%undergrad%');

        $aff = AffiliateStaff::select([
            DB::raw('affiliate_staff.last_name'),
            DB::raw('affiliate_staff.first_name'),
            DB::raw('"" AS uid'),
            DB::raw('affiliate_staff.type'),
            DB::raw('NULL AS convocation'),
            DB::raw('NULL AS pdf_completed'),
            DB::raw('affiliate_staff.start AS amii_start'),
            DB::raw('affiliate_staff.end AS amii_end'),
            DB::raw('CONCAT(supervisor_person.first_name, " ", supervisor_person.last_name) as supervisor_name'),
            DB::raw('affiliate_staff.supervisor2')
        ])
            ->leftJoin('people as supervisor_person', 'affiliate_staff.sup_id', '=', 'supervisor_person.id')
            ->where('affiliate_staff.start', '<=', $end)
            ->where(function (Builder $query) use ($start) {
                $query->where('affiliate_staff.end', '>=', $start)
                    ->orWhereNull('affiliate_staff.end');
            });

        $query->union($ugrad)->union($pdf)->union($other)->union($aff);

        return $query;
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {
        $table
            ->name('apptMetrics')
            ->defaultSort('last_name')
            ->column('last_name', sortable: true)
            ->column('first_name')
            ->column('uid')
            ->column('type')
            ->column('supervisor_name', label: "Primary Supervisor")
            ->column('supervisor2', label: "Secondary Supervisor")
            ->column('amii_start')
            ->column('amii_end')
            ->column('convocation', label: 'Final Exam Pass Date')
            ->column('pdf_completed', label: 'PDS Completed')
            ->export();
    }
}
