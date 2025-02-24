<?php

use Illuminate\Support\Facades\Route;

//User Authentication Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

//ADMIN CONTROLLERS
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminJobController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminJobApplicationController;
use App\Http\Controllers\AdminHelpdeskController;
use App\Http\Controllers\AdminDiscoverController;
use App\Http\Controllers\AdminScheduleController;
use App\Http\Controllers\AdminCalendarController;

//RECRUITER CONTROLLERS
use App\Http\Controllers\RecruiterController;
use App\Http\Controllers\RecruiterJobController;
use App\Http\Controllers\RecruiterDiscoverController;
use App\Http\Controllers\RecruiterJobApplicationController;
use App\Http\Controllers\RecruiterApplicationController;
use App\Http\Controllers\RecruiterCalendarController;
use App\Http\Controllers\RecruiterScheduleController;
use App\Http\Controllers\RecruiterUserController;
//USER CONTROLLERS
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserJobController;
use App\Http\Controllers\UserJobApplicationController;
use App\Http\Controllers\UserDiscoverController;
use App\Http\Controllers\IndexAdminController;
use App\Http\Controllers\UserCalendarController;

//Main Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\HelpdeskController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DiscoverController;
use App\Http\Controllers\JobController;


//Models
use App\Models\Job;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});
*/

Auth::routes();

//homepage punya route
Route::get('/landing', [HomeController::class, 'index'])->name('landing');

Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');

Route::get('/home', [HomeController::class, 'index'])->name('home');
/* Check page for each user */


Route::middleware('auth')->group(function () {
    //asing ikut role

    //admin
    Route::get('admin/dashboardA', [AdminController::class, 'index'])->name('admin.dashboardA');

        //admin.jobs
        Route::get('admin/jobs', [AdminJobController::class, 'index'])->name('admin.jobs.index');
        Route::get('admin/jobs/create', [AdminJobController::class, 'create'])->name('admin.jobs.create'); // Show form
        Route::get('/admin/jobs/{job}/edit', [AdminJobController::class, 'edit'])->name('admin.jobs.edit');
        Route::get('admin/jobs/{job}', [AdminJobController::class, 'show'])->name('admin.jobs.show');
        Route::put('admin/jobs/{job}', [AdminJobController::class, 'update'])->name('admin.jobs.update');
        Route::delete('admin/jobs/{job}', [AdminJobController::class, 'destroy'])->name('admin.jobs.destroy');
        Route::post('admin/jobs', [AdminJobController::class, 'store'])->name('admin.jobs.store'); // Handle form submission
        Route::get('admin/jobs/manage', [AdminJobController::class, 'manage'])->name('admin.jobs.manage');
            Route::patch('/applications/{id}/approve', [AdminJobApplicationController::class, 'approve'])->name('applications.approve');
            Route::patch('/applications/{id}/reject', [AdminJobApplicationController::class, 'reject'])->name('applications.reject');
        Route::get('/admin/jobs/pending', [AdminJobController::class, 'pendingJobs'])->name('admin.jobs.pending');
        Route::patch('/admin/jobs/{job}/update-status', [AdminJobController::class, 'updateStatus'])->name('admin.jobs.updateStatus');
        Route::patch('/jobs/{job}/close', [AdminJobController::class, 'close'])->name('admin.jobs.close');


        //admin Discover Jobs page
        Route::get('admin/discover', [AdminDiscoverController::class, 'index'])->name('admin.discover');
        

        //admin.user
        Route::resource('admin/users', AdminUserController::class);
        Route::get('admin/user', [AdminUserController::class, 'index'])->name('admin.user.index');
        Route::get('admin/user/create', [AdminUserController::class, 'create'])->name('admin.user.create')->middleware('auth'); // Show form
        Route::get('/admin/user/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.user.edit');
        Route::get('admin/user/{user}', [AdminUserController::class, 'show'])->name('admin.user.show');
        Route::put('admin/user/{user}', [AdminUserController::class, 'update'])->name('admin.user.update');
        Route::delete('admin/user/{user}', [AdminUserController::class, 'destroy'])->name('admin.user.destroy');
        Route::post('admin/user', [AdminUserController::class, 'store'])->name('admin.user.store')->middleware('auth'); // Handle form submission
        Route::post('/admin/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.user.reset-password');

        //admin.calendar
        Route::get('/admin/calendar', [App\Http\Controllers\AdminCalendarController::class, 'index'])->name('admin.calendar');
        Route::get('/admin/calendar/events', [App\Http\Controllers\AdminCalendarController::class, 'getEvents'])->name('admin.calendar.events');
        Route::get('/jobs', function () {
            $jobs = Job::all(); // Adjust the query as needed
            return response()->json($jobs->map(function ($job) {
                return [
                    'title' => $job->title, // Replace with your Job model's title column
                    'start' => $job->date_from, // Replace with the column for the job's start date
                    'end' => $job->date_to, // Replace with the column for the job's end date
                ];
            }));
        });

        //admin.jobapplication
        Route::get('admin/jobapplication', [AdminJobApplicationController::class, 'index'])->name('admin.jobapplication.index');
        Route::post('admin/jobapplication/apply/{jobId}', [AdminJobApplicationController::class, 'apply'])->name('admin.jobapplication.apply');
        Route::get('/admin/jobapplication/{job}/applications', [AdminJobApplicationController::class, 'showApplications'])
            ->name('admin.jobapplication.application');
        Route::put('/admin/jobapplications/{applicationId}/status', [AdminJobApplicationController::class, 'updateApplicationStatus'])
            ->name('admin.jobApplications.updateStatus');
        Route::get('admin/jobapplications/{applicationId}/edit', [AdminJobApplicationController::class, 'edit'])->name('admin.jobapplication.edit');
        
        //admin schedule
        Route::get('admin/schedule', [AdminScheduleController::class, 'index'])->name('admin.schedule.index'); // Show all schedules
        Route::get('admin/schedule/create', [AdminScheduleController::class, 'create'])->name('admin.schedule.create'); // Show create form
        Route::post('admin/schedule', [AdminScheduleController::class, 'store'])->name('admin.schedule.store'); // Store new schedule
        Route::get('admin/schedule/{schedule}', [AdminScheduleController::class, 'show'])->name('admin.schedule.show'); // View schedule details
        Route::get('/admin/schedule/{schedule}/edit', [AdminScheduleController::class, 'edit'])->name('admin.schedule.edit'); // Show edit form
        Route::put('admin/schedule/{schedule}', [AdminScheduleController::class, 'update'])->name('admin.schedule.update'); // Update schedule
        Route::delete('admin/schedule/{schedule}', [AdminScheduleController::class, 'destroy'])->name('admin.schedule.destroy'); // Delete schedule

        //admin user management

        //Helpdesk Routes
        Route::get('admin/helpdesk', [AdminHelpdeskController::class, 'index'])->name('admin.helpdesk.index');

        // Add these new routes here
        Route::get('admin/recruiter-requests', [App\Http\Controllers\Admin\RecruiterRequestController::class, 'index'])
            ->name('admin.recruiter-requests.index');
        Route::put('admin/recruiter-requests/{user}', [App\Http\Controllers\Admin\RecruiterRequestController::class, 'update'])
            ->name('admin.recruiter-requests.update');

    //recruiter
    Route::get('recruiter/dashboardR', [RecruiterController::class, 'index'])->name('recruiter.dashboardR');

        //recruiter Discover new Jobs page
        Route::get('recruiter/discover', [RecruiterDiscoverController::class, 'index'])->name('recruiter.discover');

        //recruiter.jobs
        Route::get('recruiter/jobs', [RecruiterJobController::class, 'index'])->name('recruiter.jobs.index');
        Route::get('recruiter/jobs/create', [RecruiterJobController::class, 'create'])->name('recruiter.jobs.create')->middleware('auth'); // Show form
        Route::get('/recruiter/jobs/{job}/edit', [RecruiterJobController::class, 'edit'])->name('recruiter.jobs.edit');
        Route::get('recruiter/jobs/{job}', [RecruiterJobController::class, 'show'])->name('recruiter.jobs.show');
        Route::put('recruiter/jobs/{job}', [RecruiterJobController::class, 'update'])->name('recruiter.jobs.update');
        Route::put('recruiter/jobs/{job}', [RecruiterJobController::class, 'update'])->name('recruiter.jobs.update');
        Route::delete('recruiter/jobs/{job}', [RecruiterJobController::class, 'destroy'])->name('recruiter.jobs.destroy');
        Route::post('recruiter/jobs', [RecruiterJobController::class, 'store'])->name('recruiter.jobs.store')->middleware('auth'); // Handle form submission
        Route::get('/recruiter/jobs/manage', [RecruiterJobController::class, 'manage'])->name('recruiter.jobs.manage');

        // Job Application routes
        Route::get('/jobapplication', [RecruiterJobApplicationController::class, 'index'])
            ->name('recruiter.jobapplication.index');
        Route::get('/jobapplication/{job}/applications', [RecruiterJobApplicationController::class, 'showApplications'])
            ->name('recruiter.jobapplication.application');
        Route::put('/jobapplications/{applicationId}/status', [RecruiterJobApplicationController::class, 'updateApplicationStatus'])
            ->name('recruiter.jobApplications.updateStatus');
            
        // Route for job applications
        Route::get('/recruiter/job/{job}/applications', [RecruiterJobApplicationController::class, 'application'])
            ->name('recruiter.jobapplication.application');


        //recruiter.user
        Route::get('recruiter/user', [RecruiterUserController::class, 'index'])->name('recruiter.user.index');
        Route::get('recruiter/user/{id}', [RecruiterUserController::class, 'show'])->name('recruiter.user.show');
        Route::get('/recruiter/user/{user}', [RecruiterUserController::class, 'show'])
        ->name('recruiter.user.show');
            
        // Schedule routes
        Route::get('/schedule', [App\Http\Controllers\RecruiterScheduleController::class, 'index'])
            ->name('recruiter.schedule');

        //recruiter.schedule
        Route::get('recruiter/schedule', [RecruiterScheduleController::class, 'index'])->name('recruiter.schedule');
    
    
    
    
        //user
    Route::get('user/dashboardU', [UserController::class, 'index'])->name('user.dashboardU');

        //user Discover new Jobs page
        Route::get('user/discover', [UserDiscoverController::class, 'index'])->name('user.discover');

        //user jobs
        Route::get('user/jobs', [UserJobController::class, 'index'])->name('user.jobs.index');
        Route::get('user/jobs/create', [UserJobController::class, 'create'])->name('user.jobs.create')->middleware('auth'); // Show form
        Route::get('/user/jobs/{job}/edit', [UserJobController::class, 'edit'])->name('user.jobs.edit');
        Route::get('user/jobs/jobmap', [UserJobController::class, 'showMap'])->name('user.jobs.jobmap');
        Route::get('user/jobs/{job}', [UserJobController::class, 'show'])->name('user.jobs.show');
        Route::put('user/jobs/{job}', [UserJobController::class, 'update'])->name('user.jobs.update');
        Route::delete('user/jobs/{job}', [UserJobController::class, 'destroy'])->name('user.jobs.destroy');
        Route::post('user/jobs', [UserJobController::class, 'store'])->name('user.jobs.store')->middleware('auth'); // Handle form submission
        Route::post('/jobs/accept-offer/{applicationId}', [UserJobController::class, 'acceptOffer'])
            ->name('user.jobs.accept-offer');
        


        //admin.jobapplication
        Route::get('user/jobapplication', [UserJobApplicationController::class, 'index'])->name('user.jobapplication.index');
        Route::post('user/jobapplication/apply/{jobId}', [UserJobApplicationController::class, 'apply'])->name('user.jobapplication.apply');
        Route::get('/applications', [UserJobApplicationController::class, 'index'])
            ->name('user.applications.index');
        Route::get('/applications/{id}/show', [UserJobApplicationController::class, 'showApplications'])
            ->name('user.applications.show');
        Route::get('/job/{jobId}/application', [UserJobApplicationController::class, 'application'])
            ->name('user.jobapplication.application');
        Route::put('/job-applications/{applicationId}/status', [UserJobApplicationController::class, 'updateApplicationStatus'])
            ->name('jobApplications.updateStatus');
        Route::delete('/job-application/{id}', [UserJobApplicationController::class, 'destroy'])->name('user.jobapplication.destroy');
        Route::post('/jobapplication/{id}/respond', [UserJobApplicationController::class, 'respond'])
            ->name('user.jobapplication.respond');

    
    
});

/* User data Page routes */
Route::post('/user', [App\Http\Controllers\UserController::class, 'store'])->name('users.store')->middleware('auth'); // Handle form submission
//Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
Route::get('/user/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create')->middleware('auth'); // Show form
Route::get('/user/{users}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
Route::get('/user/{users}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
Route::put('/user/{users}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
Route::delete('/user/{users}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

//Helpdesk Routes
Route::get('/helpdesk', [HelpdeskController::class, 'index'])->name('helpdesk.index');

//general schedule
// General Schedule routes
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index'); // Show all schedules
Route::get('/schedule/create', [ScheduleController::class, 'create'])->name('schedule.create'); // Show create form
Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store'); // Store new schedule
Route::get('/schedule/{schedule}', [ScheduleController::class, 'show'])->name('schedule.show'); // View schedule details
Route::get('/schedule/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedule.edit'); // Show edit form
Route::put('/schedule/{schedule}', [ScheduleController::class, 'update'])->name('schedule.update'); // Update schedule
Route::delete('/schedule/{schedule}', [ScheduleController::class, 'destroy'])->name('schedule.destroy'); // Delete schedule

//Notifications
Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
Route::get('/notifications/read/{id}', function ($id) {
    auth()->user()->notifications()->where('id', $id)->markAsRead();
    return redirect()->back();
});

//calendar
Route::get('calendar', [CalendarController::class, 'index'])->name('calendar');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');  // View profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');  // Edit profile
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');  // Update profile
    Route::put('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');  // Update profile photo
    Route::resource('schedule', ScheduleController::class);
    Route::get('calendar/events', [ScheduleController::class, 'getEvents']);
    Route::post('calendar/store', [ScheduleController::class, 'store']);
    Route::post('/job/{jobId}/apply', [UserJobApplicationController::class, 'apply'])
        ->name('user.jobapplication.apply');
    Route::delete('/jobapplication/{id}/delete', [UserJobApplicationController::class, 'delete'])
        ->name('user.jobapplication.delete');
    Route::post('/jobapplication/{id}/respond', [UserJobApplicationController::class, 'respond'])
        ->name('user.jobapplication.respond');
    Route::get('/recruiter-application', [RecruiterApplicationController::class, 'create'])->name('recruiter-applications.create');
    Route::post('/recruiter-application', [RecruiterApplicationController::class, 'store'])->name('recruiter-applications.store');
});

Route::get('/calendar/events', [ScheduleController::class, 'getEvents']);
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
Route::get('/calendar/schedules', [CalendarController::class, 'getSchedules']);
Route::get('/calendar/job-applications', [CalendarController::class, 'getJobApplications']);
Route::get('/calendar/user-jobs', [CalendarController::class, 'getUserJobs'])->name('calendar.user-jobs');

Route::get('/discover', [UserJobController::class, 'discover'])->name('user.discover');

