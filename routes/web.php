<?php

use App\Models\StaffAppt;
use Illuminate\Http\Request;
use App\Tables\CurrentStaffAppts;
use App\Livewire\ProjectsComponent;
use App\Tables\CurrentStudentAppts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CfsController;
use App\Http\Controllers\SBAController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AwardsController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\FellowsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\BudgetsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\StaffApptController;
use App\Http\Controllers\AffiliatesController;
use App\Http\Controllers\SpeedcodesController;
use App\Http\Controllers\CCAIHoldersController;
use App\Http\Controllers\StudentApptController;
use App\Http\Controllers\AffiliateStaffController;
use App\Http\Controllers\ContactPreferencesController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\FellowPublicationsController;
use App\Http\Controllers\FellowsViewController;
use App\Http\Controllers\TermsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware('splade')->group(function () {
    // Registers routes to support the interactive components...
    Route::spladeWithVueBridge();

    // Registers routes to support password confirmation in Form and Link components...
    Route::spladePasswordConfirmation();

    // Registers routes to support Table Bulk Actions and Exports...
    Route::spladeTable();

    // Registers routes to support async File Uploads with Filepond...
    Route::spladeUploads();

    Route::middleware('auth')->group(function () {
        // Route::get('/', [StudentController::class, 'index']);
        // Route::get('/', function () {
        //     return view('dashboard');
        // })->name('home');

        // Route::get('/dashboard', function () {
        //     return view('dashboard');
        // })->middleware(['verified'])->name('dashboard');

        Route::get('/', [DashboardController::class, 'index'])->middleware(['verified'])->name('home');
        Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['verified'])->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/people', [PeopleController::class, 'index']);
        Route::get('/people/missing_salary', [PeopleController::class, 'missingSalaryPeople'])->name('people.missing_salary');
        Route::get('/people/missing_salary/transactions/{uid}/{project}/{program}', [PeopleController::class, 'missingSalaryTransactions'])->name('people.missing_salary_transactions');
        Route::get('/people/missing_salary/add', [PeopleController::class, 'showAddMissingPersonForm'])->name('people.missing_salary_add_form');
        Route::post('/people/missing_salary/add', [PeopleController::class, 'addMissingPerson'])->name('people.missing_salary_add');
        Route::post('/people/missing_salary/ignore', [PeopleController::class, 'ignoreMissingPerson'])->name('people.missing_salary_ignore');
        Route::post('/people/missing_salary/unignore', [PeopleController::class, 'unignoreMissingPerson'])->name('people.missing_salary_unignore');
        Route::post('/people/missing_salary/notes', [PeopleController::class, 'updateMissingNotes'])->name('people.missing_salary_notes');
        Route::get('/people/view/{person}', [PeopleController::class, 'show'])->name('people.show');
        Route::post('/people/view/{person}', [PeopleController::class, 'update'])->name('people.update');
        Route::post('/people/find', [PeopleController::class, 'find'])->name('people.find');
        Route::get('/people/random_uid', [PeopleController::class, 'getRandomUID'])->name('people.random_uid');

        Route::get('/students', [StudentController::class, 'index'])->name('students');
        Route::get('/student/create', [StudentController::class, 'create'])->name('students.new');
        Route::post('/student/create', [StudentController::class, 'store'])->name('students.create');
        Route::get('/student/create/{person}', [StudentController::class, 'createRecord'])->name('students.new_record');
        Route::post('/student/create/{person}', [StudentController::class, 'storeRecord'])->name('students.create_record');
        Route::get('/student/edit/{student}', [StudentController::class, 'edit'])->name('student.edit');
        Route::post('/student/edit/{student}', [StudentController::class, 'update'])->name('student.update');
        Route::get('/student/delete/{student}', [StudentController::class, 'destroy'])->name('student.destroy');
        Route::get('/student/appointment/create/{student}', [StudentApptController::class, 'create'])->name('student.appt.new');
        Route::post('/student/appointment/create/{student}', [StudentApptController::class, 'store'])->name('student.appt.create');
        Route::get('/student/appointment/edit/{appt}', [StudentApptController::class, 'edit'])->name('student.appt.edit');
        Route::post('/student/appointment/edit/{appt}', [StudentApptController::class, 'update'])->name('student.appt.update');
        Route::get('/student/appointment/rates/options', [StudentApptController::class, 'rateOptions'])->name('student.appt.rate_options');
        Route::get('/student/appointment/delete/{appt}', [StudentApptController::class, 'destroy'])->name('student.appt.delete');

        Route::get('/staff', [StaffController::class, 'index'])->name('staff');
        Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.new');
        Route::post('/staff/create', [StaffController::class, 'store'])->name('staff.create');
        Route::get('/staff/create/{person}', [StaffController::class, 'createRecord'])->name('staff.new_record');
        Route::post('/staff/create/{person}', [StaffController::class, 'storeRecord'])->name('staff.create_record');
        Route::get('/staff/edit/{staff}', [StaffController::class, 'edit'])->name('staff.edit');
        Route::post('/staff/edit/{staff}', [StaffController::class, 'update'])->name('staff.update');
        Route::get('/staff/appt/create/{staff}', [StaffApptController::class, 'create'])->name('staff.appt.create');
        Route::post('/staff/appt/create/{staff}', [StaffApptController::class, 'store'])->name('staff.appt.store');
        Route::get('/staff/appt/edit/{appt}', [StaffApptController::class, 'edit'])->name('staff.appt.edit');
        Route::post('/staff/appt/edit/{appt}', [StaffApptController::class, 'update'])->name('staff.appt.update');
        Route::delete('/staff/appt/{appt}', [StaffApptController::class, 'destroy'])->name('staff.appt.delete');
        //Route::get('/staff/delete/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');

        Route::get('/fellows', [FellowsController::class, 'index'])->name('fellows');
        Route::get('/fellows/create', [FellowsController::class, 'create'])->name('fellows.new');
        Route::post('/fellows/create', [FellowsController::class, 'store'])->name('fellows.create');
        Route::post('/fellows/update/{fellow}', [FellowsController::class, 'update'])->name('fellows.update');

        Route::get('/references', [ReferenceController::class, 'index'])->name('reference');
        Route::get('/references/create/{table}', [ReferenceController::class, 'create'])->name('reference.create');
        Route::post('/references/store/{table}', [ReferenceController::class, 'store'])->name('reference.store');
        Route::get('/reference/edit/{table}/{item}', [ReferenceController::class, 'edit'])->name('reference.edit');
        Route::post('/reference/edit/{table}/{item}', [ReferenceController::class, 'update'])->name('reference.update');

        Route::post('/terms/find', [TermsController::class, 'find'])->name('terms.find');

        Route::get('/rates', [ReferenceController::class, 'rates'])->name('rates');

        Route::get('/speedcodes', [SpeedcodesController::class, 'index'])->name('speedcodes');
        Route::get('/speedcodes/all', [SpeedcodesController::class, 'all'])->name('speedcodes.all');
        Route::get('/speedcodes/create', [SpeedcodesController::class, 'create'])->name('speedcodes.create');
        Route::post('/speedcodes/store', [SpeedcodesController::class, 'store'])->name('speedcodes.store');
        Route::get('/speedcodes/edit/{code}', [SpeedcodesController::class, 'edit'])->name('speedcodes.edit');
        Route::post('/speedcodes/edit/{code}', [SpeedcodesController::class, 'update'])->name('speedcodes.update');
        Route::post('/speedcodes/find', [SpeedcodesController::class, 'find'])->name('speedcodes.find');

        Route::get('/sba', [SBAController::class, 'index'])->name('sba');
        Route::get('/sba/create', [SBAController::class, 'create'])->name('sba.create');
        Route::post('/sba/store', [SBAController::class, 'store'])->name('sba.store');
        Route::get('/sba/edit/{sba}', [SBAController::class, 'edit'])->name('sba.edit');
        Route::post('/sba/edit/{sba}', [SBAController::class, 'update'])->name('sba.update');

        Route::get('/cfs', [CfsController::class, 'index'])->name('cfs');
        Route::get('/cfs/create', [CfsController::class, 'create'])->name('cfs.create');
        Route::post('/cfs/store', [CfsController::class, 'store'])->name('cfs.store');
        Route::get('/cfs/edit/{cfs}', [CfsController::class, 'edit'])->name('cfs.edit');
        Route::post('/cfs/edit/{cfs}', [CfsController::class, 'update'])->name('cfs.update');

        Route::get('/appts/student', function() {
            return view('studentappts.index', [
                'appts' => CurrentStudentAppts::class,
            ]);
        })->name('student_appts');

        Route::get('/appts/staff', function() {
            return view('staffappts.index', [
                'appts' => CurrentStaffAppts::class,
            ]);
        })->name('staff_appts');

        Route::get('/awards', [AwardsController::class, 'index'])->name('awards');
        Route::get('/awards/edit/{award}', [AwardsController::class, 'edit'])->name('awards.edit');
        Route::post('/awards/update/{award}', [AwardsController::class, 'update'])->name('awards.update');
        Route::get('/awards/create/{person}', [AwardsController::class, 'create'])->name('awards.create');
        Route::post('/awards/store/{person}', [AwardsController::class, 'store'])->name('awards.store');

        Route::get('/affiliates', [AffiliatesController::class, 'index'])->name('affiliates');
        Route::get('/affiliates/edit/{affiliate}', [AffiliatesController::class, 'edit'])->name('affiliates.edit');
        Route::post('/affiliates/update/{affiliate}', [AffiliatesController::class, 'update'])->name('affiliates.update');
        Route::get('/affiliates/create', [AffiliatesController::class, 'create'])->name('affiliates.create');
        Route::post('/affiliates/store', [AffiliatesController::class, 'store'])->name('affiliates.store');

        Route::get('/affiliatestaff', [AffiliateStaffController::class, 'index'])->name('affiliate_staff');
        Route::get('/affiliatestaff/edit/{affiliateStaff}', [AffiliateStaffController::class, 'edit'])->name('affiliate_staff.edit');
        Route::post('/affiliatestaff/update/{affiliateStaff}', [AffiliateStaffController::class, 'update'])->name('affiliate_staff.update');
        Route::get('/affiliatestaff/create', [AffiliateStaffController::class, 'create'])->name('affiliate_staff.create');
        Route::post('/affiliatestaff/store', [AffiliateStaffController::class, 'store'])->name('affiliate_staff.store');

        Route::get('/projects', [ProjectsController::class, 'index'])->name('projects');
        Route::post('/projects/import/rhp', [ProjectsController::class, 'import'])->name('projects.import');
        Route::post('/projects/import/etrac', [ProjectsController::class, 'importEtrac'])->name('projects.importEtrac');
        Route::post('/projects/import/salary', [ProjectsController::class, 'importSalary'])->name('projects.importSalary');
        Route::get('/projects/edit/{projects}', [ProjectsController::class, 'edit'])->name('projects.edit');
        Route::post('/projects/update/{projects}', [ProjectsController::class, 'update'])->name('projects.update');
        Route::get('/projects/create', [ProjectsController::class, 'create'])->name('projects.create');
        Route::post('/projects/store', [ProjectsController::class, 'store'])->name('projects.store');
        Route::get('/projects/add/{project}', [ProjectsController::class, 'add'])->name('projects.add');
        Route::get('/projects/ignore/{project}', [ProjectsController::class, 'ignore'])->name('projects.ignore');
        Route::post('/projects/edit/{project}/checkbox', [ProjectsController::class, 'checkboxUpdate'])->name('projects.checkboxUpdate');
        Route::get('/projects/complete/{project}', [ProjectsController::class, 'complete'])->name('projects.complete');
        Route::get('/projects/ignoreMissing/{project}', [ProjectsController::class, 'ignoreMissing'])->name('projects.ignoreMissing');
        // Route::get('/projects/livewire', ProjectsComponent::class)->name('projects.livewire');

        Route::get('/metrics/appts', [PeopleController::class, 'apptMetrics'])->name('metrics.appts');
        Route::get('/claimed_students', [StudentApptController::class, 'claimedStudents'])->name('claimed_students');

        Route::get('/contact', [ContactPreferencesController::class, 'index'])->name('contact');
        Route::get('/contact/edit/{contact}', [ContactPreferencesController::class, 'edit'])->name('contact.edit');
        Route::post('/contact/update/{contact}', [ContactPreferencesController::class, 'update'])->name('contact.update');
        Route::get('/contact/create', [ContactPreferencesController::class, 'create'])->name('contact.create');
        Route::post('/contact/store', [ContactPreferencesController::class, 'store'])->name('contact.store');

        Route::get('/visitors', [VisitorController::class, 'index'])->name('visitor');
        Route::get('/visitors/edit/{visitor}', [VisitorController::class, 'edit'])->name('visitor.edit');
        Route::post('/visitors/update/{visitor}', [VisitorController::class, 'update'])->name('visitor.update');
        Route::get('/visitors/create', [VisitorController::class, 'create'])->name('visitor.create');
        Route::post('/visitors/store', [VisitorController::class, 'store'])->name('visitor.store');

        Route::get('/admin', [AdminController::class, 'index'])->name('admin');
        Route::get('/admin/new_user', [AdminController::class, 'create'])->name('admin.newuser');
        Route::get('/admin/navigation', [AdminController::class, 'navigation'])->name('admin.navigation');
        Route::get('/admin/view-as-fellow', [AdminController::class, 'viewAsFellow'])->name('admin.view_as_fellow');
        Route::post('/admin/view-as-fellow', [AdminController::class, 'setViewAsFellow'])->name('admin.set_view_as_fellow');
        Route::post('/admin/view-as-fellow/clear', [AdminController::class, 'clearViewAsFellow'])->name('admin.clear_view_as_fellow');
        Route::get('/admin/reset_password/{user}', [AdminController::class, 'resetPassword'])->name('admin.resetpassword');
        Route::post('/admin/reset_password/{user}', [AdminController::class, 'updatePassword'])->name('admin.updatepassword');

        Route::get('/admin/navigation/create', [AdminController::class, 'navCreate'])->name('admin.nav_create');
        Route::post('/admin/navigation/create', [AdminController::class, 'navStore'])->name('admin.nav_store');
        Route::post('/admin/navigation/edit/{id}', [AdminController::class, 'navUpdate'])->name('admin.nav_edit');
        Route::delete('/admin/navigation/{id}', [AdminController::class, 'navDelete'])->name('admin.nav_delete');

        Route::post('/admin/navcategory/edit/{id}', [AdminController::class, 'catUpdate'])->name('admin.cat_edit');

        Route::delete('/admin/user/delete/{id}', [AdminController::class, 'destroy'])->name('admin.deleteuser');
        Route::post('/admin/user/role/{id}', [AdminController::class, 'update'])->name('admin.changerole');

        Route::get('/admin/permissions', [AdminController::class, 'perms'])->name('admin.perms');

        Route::post('/admin/permission/edit/{id}', [AdminController::class, 'permUpdate'])->name('admin.permUpdate');
        Route::get('/admin/permission/create', [AdminController::class, 'newPerm'])->name('admin.newPerm');
        Route::post('/admin/permission/create', [AdminController::class, 'createPerm'])->name('admin.createPerm');
        Route::delete('/admin/permission/delete/{id}', [AdminController::class, 'deletePerm'])->name('admin.deletePerm');

        Route::post('/admin/role/edit/{id}', [AdminController::class, 'roleUpdate'])->name('admin.roleUpdate');
        Route::get('/admin/role/create', [AdminController::class, 'newRole'])->name('admin.newRole');
        Route::post('/admin/role/create', [AdminController::class, 'createRole'])->name('admin.createRole');
        Route::delete('/admin/role/delete/{id}', [AdminController::class, 'deleteRole'])->name('admin.deleteRole');

        Route::get('/holder', [CCAIHoldersController::class, 'index'])->name('ccai');
        Route::get('/holder/create', [CCAIHoldersController::class, 'create'])->name('ccai.create');
        Route::post('/holder/store', [CCAIHoldersController::class, 'store'])->name('ccai.store');
        Route::get('/holder/edit/{ccai}', [CCAIHoldersController::class, 'edit'])->name('ccai.edit');
        Route::post('/holder/edit/{ccai}', [CCAIHoldersController::class, 'update'])->name('ccai.update');
        Route::delete('/holder/delete/{ccai}', [CCAIHoldersController::class, 'destroy'])->name('ccai.delete');

        Route::get('/forecasting', [ProjectsController::class, 'forecasting'])->name('forecasting');
        Route::get('/forecasting/edit/{projects}/{end}', [ProjectsController::class, 'editForecasting'])->name('forecasting.edit');

        Route::get('/payments', [PaymentsController::class, 'index'])->name('payments');
        Route::get('/payments/create/{person}', [PaymentsController::class, 'create'])->name('payments.create');
        Route::get('/payments/create', [PaymentsController::class, 'createGeneric'])->name('payments.createGeneric');
        Route::post('/payments/store/{person}', [PaymentsController::class, 'store'])->name('payments.store');
        Route::post('/payments/store', [PaymentsController::class, 'storeGeneric'])->name('payments.storeGeneric');
        Route::get('/payments/edit/{payments}', [PaymentsController::class, 'edit'])->name('payments.edit');
        Route::get('/payments/editGen/{payments}', [PaymentsController::class, 'editGeneric'])->name('payments.editGeneric');
        Route::post('/payments/edit/{payments}', [PaymentsController::class, 'update'])->name('payments.update');
        Route::post('/payments/editGen/{payments}', [PaymentsController::class, 'updateGeneric'])->name('payments.updateGeneric');

        Route::get('/budgets', [BudgetsController::class, 'index'])->name('budgets');
        Route::get('/budgets/create', [BudgetsController::class, 'create'])->name('budgets.create');
        Route::get('/budgets/create/{person}', [BudgetsController::class, 'createFromPerson'])->name('budgets.create.person');
        Route::get('/budgets/create/project/{projects}', [BudgetsController::class, 'createFromProject'])->name('budgets.create.project');
        Route::post('/budgets/store', [BudgetsController::class, 'store'])->name('budgets.store');
        Route::post('/budgets/store/{person}', [BudgetsController::class, 'storeFromPerson'])->name('budgets.store.person');
        Route::post('/budgets/store/project/{projects}', [BudgetsController::class, 'storeFromProject'])->name('budgets.store.project');
        Route::get('/budgets/edit/{budget}', [BudgetsController::class, 'edit'])->name('budgets.edit');
        Route::post('/budgets/update/{budget}', [BudgetsController::class, 'update'])->name('budgets.update');

        Route::get('/fellow/people', [FellowsViewController::class, 'staffIndex'])->name('fellowsView.staff');
        Route::get('/fellow/people/all', [FellowsViewController::class, 'allStaffIndex'])->name('fellowsView.allStaff');
        Route::get('/fellow/speedcodes', [FellowsViewController::class, 'speedcodesIndex'])->name('fellowsView.speedcodes');
        Route::get('/fellow/speedcodes/view/{project}', [FellowsViewController::class, 'viewSpeedcode'])->name('fellowsView.viewSpeedcode');
        Route::post('/fellow/speedcodes/edit/{project}', [FellowsViewController::class, 'updateProject'])->name('fellowsView.updateProject');

        Route::get('/fellow/budget', [FellowsViewController::class, 'budgetIndex'])->name('fellowsView.budget');
        Route::get('/fellow/budget/export', [FellowsViewController::class, 'budgetExportAll'])->name('fellowsView.budgetExportAll');
        Route::get('/fellow/budget/{project}', [FellowsViewController::class, 'budgetReport'])->name('fellowsView.budgetReport');

        Route::get('/contacts', [ContactsController::class, 'index'])->name('contacts');
        Route::get('/contacts/create', [ContactsController::class, 'create'])->name('contacts.create');
        Route::post('/contacts/store', [ContactsController::class, 'store'])->name('contacts.store');
        Route::get('/contacts/edit/{contact}', [ContactsController::class, 'edit'])->name('contacts.edit');
        Route::post('/contacts/edit/{contact}', [ContactsController::class, 'update'])->name('contacts.update');

        Route::get('/publications', [FellowPublicationsController::class, 'index'])->name('publications');
        Route::get('/publications/create', [FellowPublicationsController::class, 'create'])->name('publications.create');
        Route::post('/publications/store', [FellowPublicationsController::class, 'store'])->name('publications.store');
        Route::get('/publications/edit/{publication}', [FellowPublicationsController::class, 'edit'])->name('publications.edit');
        Route::post('/publications/edit/{publication}', [FellowPublicationsController::class, 'update'])->name('publications.update');

        Route::get('/create-token', function (Request $request) {
            // $token = $request->user()->createToken('RAP');
            $token = "test";
            dd($token);
        });
        Route::get('/misc', function() {
            phpinfo();
        })->name('phpinfo');
    });

    require __DIR__.'/auth.php';
});
