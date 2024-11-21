<?php
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\Doctor;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });


Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');





// Route::middleware('admin')->group(function () {
//     Route::get('/admin/dashboard', [HomeController::class, 'dashboard'])->name('admin.dashboard');
// });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard')->middleware(['auth','doctor']);
// Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard')->middleware(['auth','doctor']);
// Route::get('/doctor/dashboard', [HomeController::class, 'doctorDashboard'])->name('doctor.dashboard')->middleware(['auth','doctor']);






Route::middleware(['doctor'])->group(function () {
    // Doctor-only routes
    Route::get('/doctor/dashboard', [HomeController::class, 'doctorDashboard'])->name('doctor.dashboard');
    Route::get('/doctor/profile', [HomeController::class, 'doctorProfile'])->name('doctor.profile');
    Route::get('/doctor/appointment', [HomeController::class, 'doctorAppointment'])->name('doctor.appointment');
    Route::get('/doctor/schedule', [HomeController::class, 'doctorSchedule'])->name('doctor.schedule');

    Route::post('saveSchedule', [DoctorController::class, 'saveSchedule'])->name('schedule.store');
    Route::patch('/schedules/{schedule}', [DoctorController::class, 'updateSched'])->name('schedule.update'); 
    Route::delete('/schedules/{schedule}', [DoctorController::class, 'destroySched'])->name('schedule.destroy');  

    Route::post('/appointments/{id}/update', [DoctorController::class, 'updateAppointment'])->name('doctor.updateAppointment');



});

Route::middleware(['patient'])->group(function () {

    Route::get('/patient/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/patient/profile', [HomeController::class, 'patientProfile'])->name('patient.profile');
    Route::get('/patient/about', [HomeController::class, 'patientAbout'])->name('patient.about');
    Route::get('/patient/services', [HomeController::class, 'patientServices'])->name('patient.services');
    Route::get('/patient/departments', [HomeController::class, 'patientDepartments'])->name('patient.departments');
    Route::get('/patient/doctors', [HomeController::class, 'patientDoctors'])->name('patient.doctors');
    Route::get('/patient/contact', [HomeController::class, 'patientContact'])->name('patient.contact');
    Route::get('/patient/myappointment', [HomeController::class, 'patientMyappointment'])->name('patient.myappointment');
    Route::post('/submit-feedback', [PatientController::class, 'submitFeedback'])->name('submit.feedback');
    
    // Route::get('/patient/chatbot', [ChatbotController::class, 'patientChatbot'])->name('patient.chatbot');
    // Patient-only routes
  

    Route::get('/departments/{department}', [HomeController::class, 'showByDepartment'])->name('departments.doctors');
    Route::get('/doctor/{docname}', [HomeController::class, 'showDoctor'])->name('doctor.details');


    Route::post('/book-schedule', [PatientController::class, 'bookSchedule'])->name('schedule.book');


Route::delete('/bookings/{booking}', [PatientController::class, 'destroyBooking'])->name('booking.destroy');
Route::get('/patient/pending-bookings-count', [PatientController::class, 'pendingBookingsCount'])->name('patient.pendingBookingsCount');


});

Route::get('/patient/chatbot-2349snskdgd/g/dgdssd/s/sd5dggdgd@sd', [ChatbotController::class, 'patientChatbot'])->name('patient.chatbot');



Route::middleware(['admin'])->group(function () {
    // Admin-only routes

    Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [HomeController::class, 'adminProfile'])->name('admin.profile');
    Route::get('/admin/appointment', [HomeController::class, 'adminAppointment'])->name('admin.appointment');
    Route::get('/admin/schedule', [HomeController::class, 'adminSchedule'])->name('admin.schedule');
    Route::get('/admin/users', [HomeController::class, 'adminUsers'])->name('admin.users');
    Route::get('/admin/settings', [HomeController::class, 'adminSettings'])->name('admin.settings');
    Route::get('/admin/services', [HomeController::class, 'adminServices'])->name('admin.services');
    Route::get('/admin/departments', [HomeController::class, 'adminDepartments'])->name('admin.departments');
    Route::get('/admin/doctors', [HomeController::class, 'adminDoctors'])->name('admin.doctors');
    Route::get('/admin/research_lab', [HomeController::class, 'adminResearch_lab'])->name('admin.research_labs');
    Route::get('/admin/award', [HomeController::class, 'adminAward'])->name('admin.awards');
    Route::get('/admin/room', [HomeController::class, 'adminRoom'])->name('admin.rooms');
    Route::get('/admin/specialty', [HomeController::class, 'adminSpecialty'])->name('admin.specialtys');

    Route::post('store', [AdminController::class, 'store'])->name('users.store'); 
    Route::patch('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update'); 
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy'); 

    Route::post('storeH', [AdminController::class, 'storeH'])->name('header.store'); 
    Route::post('/headers/{header}', [AdminController::class, 'updateH'])->name('header.update'); 
    Route::delete('/headers/{header}', [AdminController::class, 'destroyH'])->name('header.destroy'); 

    Route::post('storeF', [AdminController::class, 'storeF'])->name('footer.store'); 
    Route::patch('/footers/{footer}', [AdminController::class, 'updateF'])->name('footer.update');
    Route::delete('/footers/{footer}', [AdminController::class, 'destroyF'])->name('footer.destroy');
    
    Route::post('storeS', [AdminController::class, 'storeS'])->name('service.store'); 
    Route::post('/services/{service}', [AdminController::class, 'updateS'])->name('service.update'); 
    Route::delete('/services/{service}', [AdminController::class, 'destroyS'])->name('service.destroy'); 

    Route::post('storeDep', [AdminController::class, 'storeDep'])->name('department.store'); 
    Route::post('/departments/{department}', [AdminController::class, 'updateDep'])->name('department.update'); 
    Route::delete('/departments/{department}', [AdminController::class, 'destroyDep'])->name('department.destroy'); 

    Route::post('storeDoc', [AdminController::class, 'storeDoc'])->name('doctor.store'); 
    Route::post('/doctors/{doctor}', [AdminController::class, 'updateDoc'])->name('doctor.update'); 
    Route::delete('/doctors/{doctor}', [AdminController::class, 'destroyDoc'])->name('doctor.destroy'); 



    Route::post('storeRes', [AdminController::class, 'storeRes'])->name('research.store'); 
    Route::post('/research_labs/{research_lab}', [AdminController::class, 'updateRes'])->name('research.update'); 
    Route::delete('/research_labs/{research_lab}', [AdminController::class, 'destroyRes'])->name('research.destroy'); 


    Route::post('storeAward', [AdminController::class, 'storeAward'])->name('award.store'); 
    Route::patch('/awards/{award}', [AdminController::class, 'updateAward'])->name('award.update'); 
    Route::delete('/awards/{award}', [AdminController::class, 'destroyAward'])->name('award.destroy'); 

    Route::post('storeRoom', [AdminController::class, 'storeRoom'])->name('room.store'); 
    Route::patch('/rooms/{room}', [AdminController::class, 'updateRoom'])->name('room.update'); 
    Route::delete('/rooms/{room}', [AdminController::class, 'destroyRoom'])->name('room.destroy'); 

    Route::post('storeSpec', [AdminController::class, 'storeSpec'])->name('specialty.store'); 
    Route::patch('/specialties/{specialty}', [AdminController::class, 'updateSpec'])->name('specialty.update'); 
    Route::delete('/specialties/{specialty}', [AdminController::class, 'destroySpec'])->name('specialty.destroy'); 



  

    // Route::inertia('/admin/profile', 'Admin/Profile')->name('admin.profile'); 
});
