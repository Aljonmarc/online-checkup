<?php

namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;
use App\Models\Award;
use App\Models\Booking;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\User;
use App\Models\Header;
use App\Models\Service;
use App\Models\Footer;
use App\Models\Research_lab;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Specialty;
use App\Models\Testimonial;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function welcome(): Response
    {

        $doctorCount = Doctor::count();
        $departmentCount = Department::count();
        $researchlabCount = Research_lab::count();
        $awardCount = Award::count();

        $headers = Header::all();
        $footers = Footer::all();
        $services = Service::all();
        $departments = Department::all();
        $doctors = Doctor::all();

        // Eager load the 'user' relationship to get the user's name
        $testimonials = Testimonial::with('user')->get();

        return Inertia::render(
            'Welcome',

            [

                'headers' => $headers,
                'footers' => $footers,
                'services' => $services,
                'departments' => $departments,
                'doctors' => $doctors,
                'testimonials' => $testimonials,

                'doctorCount' => $doctorCount,
                'departmentCount' => $departmentCount,
                'doctorCount' => $doctorCount,
                'researchlabCount' => $researchlabCount,
                'awardCount' => $awardCount,

                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,


            ]
        );
    }


    public function adminDashboard(): Response
    {
        $userCount = User::count();
        $bookCount = Booking::count();
        $servicesCount = Service::count();
        $departmentsCount = Department::count();
        $doctorsCount = Doctor::count();
        $research_labsCount = Research_lab::count();
        $awardsCount = Award::count();
        $roomsCount = Room::count();
        $specialtyCount = Specialty::count();

        $scheduleCount = Schedule::count();

        return Inertia::render('admin/dashboard', [
            'userCount' => $userCount,
            'bookCount' => $bookCount,
            'servicesCount' => $servicesCount,
            'departmentsCount' => $departmentsCount,
            'doctorsCount' => $doctorsCount,
            'research_labsCount' => $research_labsCount,
            'awardsCount' => $awardsCount,
            'roomsCount' => $roomsCount,
            'specialtyCount' => $specialtyCount,
            'scheduleCount' => $scheduleCount,
        ]);
    }

    public function adminProfile(): Response
    {
        return Inertia::render('admin/profile');
    }

    public function adminAppointment(Request $request): Response
    {
        

         // Get the search term from the request
         $search = $request->input('search');
        
         // Query bookings with pagination and filtering by search term and user-specific filter
         $bookings = Booking::query()
             ->when($search, function ($query, $search) {
                 $query->where('patient_name', 'like', "%{$search}%")
                 ->orWhere('doc_name', 'like', "%{$search}%")
                     ->orWhere('start_time', 'like', "%{$search}%")
                     ->orWhere('admin_status', 'like', "%{$search}%")
                     ->orWhere('status', 'like', "%{$search}%");
             })
             ->latest()
             ->paginate(10); // Paginate with 10 bookings per page
     
         return inertia('admin/appointment', [
             'bookings' => $bookings,
             'search' => $search,
         ]);
    }


    public function adminSchedule(Request $request): Response
    {
      

          // Get the search term from the request
          $search = $request->input('search');

          // Fetch schedules for the logged-in doctor (user_id = $userid)
          $schedules = Schedule::query()
              ->when($search, function ($query, $search) {
                  $query->where('start_date', 'like', "%{$search}%")
                      ->orWhere('end_time', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%");
              })
              ->latest()
              ->paginate(10); // Paginate with 10 schedules per page

          // Return the schedules to the Inertia view
          return inertia('admin/schedule', [
              'schedules' => $schedules,
              'search' => $search,
          ]);
    }


    public function adminUsers(Request $request): Response
    {
        // Get the search term from the request
        $search = $request->input('search');

        // Query users with pagination, filtering by search term if provided
        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10); // Paginate with 10 users per page

        // Return the filtered or unfiltered users to the Inertia view
        return inertia('admin/users', [
            'users' => $users,
            'search' => $search,
        ]);
    }



    public function adminSettings(): Response
    {
        $headers = Header::all();
        $footers = Footer::all();
        return Inertia::render('admin/settings', compact('headers', 'footers'));
    }

    public function adminServices(Request $request): Response
    {
        // Get the search term from the request
        $search = $request->input('search');

        // Query users with pagination, filtering by search term if provided
        $services = Service::query()
            ->when($search, function ($query, $search) {
                $query->where('stitle', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10); // Paginate with 10 users per page

        // Return the filtered or unfiltered users to the Inertia view
        return inertia('admin/services', [
            'services' => $services,
            'search' => $search,
        ]);
    }

    public function adminDepartments(Request $request): Response
    {

        // Get the search term from the request
        $search = $request->input('search');

        // Query users with pagination, filtering by search term if provided
        $departments = Department::query()
            ->when($search, function ($query, $search) {
                $query->where('titledep', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10); // Paginate with 10 users per page

        // Return the filtered or unfiltered users to the Inertia view
        return inertia('admin/departments', [
            'departments' => $departments,
            'search' => $search,

        ]);
    }



    public function adminDoctors(Request $request): Response
    {

        // Get the search term from the request
        $search = $request->input('search');

        $users = User::where('role', 'doctor')->get(['id', 'name', 'phone']);
        $departments = Department::all(); // Adjust the model and query as necessary
        $rooms = Room::all(); // Adjust the model and query as necessary
        $specialties = Specialty::all(); // Adjust the model and query as necessary

        // Query users with pagination, filtering by search term if provided
        $doctors = Doctor::query()
            ->when($search, function ($query, $search) {
                $query->where('docname', 'like', "%{$search}%")
                    ->orWhere('docroom', 'like', "%{$search}%")
                    ->orWhere('docspec', 'like', "%{$search}%")
                    ->orWhere('docphone', 'like', "%{$search}%")
                    ->orWhere('docdep', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10); // Paginate with 10 users per page

        // Return the filtered or unfiltered users to the Inertia view
        return inertia('admin/doctors', [
            'doctors' => $doctors,
            'users' => $users,
            'search' => $search,
            'departments' => $departments, // Add departments here directly
            'rooms' => $rooms,
            'specialties' => $specialties,
        ]);
    }

    public function  adminResearch_lab(Request $request): Response
    {
        // Get the search term from the request
        $search = $request->input('search');


        // Query users with pagination, filtering by search term if provided
        $research_labs = Research_lab::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10); // Paginate with 10 users per page

        // Return the filtered or unfiltered users to the Inertia view
        return inertia('admin/research_lab', [
            'research_labs' => $research_labs,

            'search' => $search,

        ]);
    }

    public function  adminAward(Request $request): Response
    {

        // Get the search term from the request
        $search = $request->input('search');



        // Query users with pagination, filtering by search term if provided
        $awards = Award::query()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('year', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10); // Paginate with 10 users per page

        // Return the filtered or unfiltered users to the Inertia view
        return inertia('admin/award', [
            'awards' => $awards,
            'search' => $search,

        ]);
    }



    public function  adminRoom(Request $request): Response
    {

        // Get the search term from the request
        $search = $request->input('search');

        // Query users with pagination, filtering by search term if provided
        $rooms = Room::query()
            ->when($search, function ($query, $search) {
                $query->where('room_number', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10); // Paginate with 10 users per page


        // Return the filtered or unfiltered users to the Inertia view
        return inertia('admin/room', [
            'rooms' => $rooms,
            'search' => $search,

        ]);
    }


    public function  adminSpecialty(Request $request): Response
    {

        // Get the search term from the request
        $search = $request->input('search');


        // Query users with pagination, filtering by search term if provided
        $specialties = Specialty::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10); // Paginate with 10 users per page


        // Return the filtered or unfiltered users to the Inertia view
        return inertia('admin/specialty', [
            'specialties' => $specialties,

            'search' => $search,

        ]);
    }



    // <!--admin here..--!>


    public function header(): Response
    {


        return Inertia::render('patient/header/header');
    }


    public function dashboard(): Response
    {
        $headers = Header::all();
        $footers = Footer::all();
        $footers = Footer::all();
        $services = Service::all();
        $departments = Department::all();
        $doctors = Doctor::all();


        $doctorCount = Doctor::count();
        $departmentCount = Department::count();
        $researchlabCount = Research_lab::count();
        $awardCount = Award::count();

        // Eager load the 'user' relationship to get the user's name
        $testimonials = Testimonial::with('user')->get();

        return Inertia::render('Dashboard', [

            'headers' => $headers,
            'footers' => $footers,
            'services' => $services,
            'departments' => $departments,
            'doctors' => $doctors,
            'testimonials' => $testimonials,


            'doctorCount' => $doctorCount,
            'departmentCount' => $departmentCount,
            'doctorCount' => $doctorCount,
            'researchlabCount' => $researchlabCount,
            'awardCount' => $awardCount,


        ]);
    }

    public function patientProfile(): Response
    {
        $headers = Header::all();
        $footers = Footer::all();
        return Inertia::render('patient/profile', compact('headers', 'footers',));
    }

    public function patientAbout(): Response
    {

        $doctorCount = Doctor::count();
        $departmentCount = Department::count();
        $researchlabCount = Research_lab::count();
        $awardCount = Award::count();

        $headers = Header::all();
        $footers = Footer::all();
        return Inertia::render('patient/about', [

            'headers' => $headers,
            'footers' => $footers,

            'doctorCount' => $doctorCount,
            'departmentCount' => $departmentCount,
            'doctorCount' => $doctorCount,
            'researchlabCount' => $researchlabCount,
            'awardCount' => $awardCount,


        ]);
    }

    public function patientServices(): Response
    {
        $headers = Header::all();
        $footers = Footer::all();
        $services = Service::all();
        return Inertia::render('patient/services', [

            'headers' => $headers,
            'footers' => $footers,
            'services' => $services,


        ]);
    }

    public function patientDepartments(): Response
    {
        $headers = Header::all();
        $footers = Footer::all();
        $departments = Department::all();
        return Inertia::render('patient/departments', [

            'headers' => $headers,
            'footers' => $footers,
            'departments' => $departments,


        ]);
    }

    public function patientDoctors(): Response
    {
        $headers = Header::all();
        $footers = Footer::all();
        $doctors = Doctor::all();
        return Inertia::render('patient/doctors',  [

            'headers' => $headers,
            'footers' => $footers,
            'doctors' => $doctors,


        ]);
    }

    public function patientContact(): Response
    {
        $headers = Header::all();
        $footers = Footer::all();
        return Inertia::render('patient/contact', compact('headers', 'footers'));
    }

    public function patientMyappointment(Request $request): Response
    {
      
      
        if (Auth::check()) {
            $userid = Auth::user()->id;

            $headers = Header::all();
            $footers = Footer::all();
        
            // Get the search term from the request
            $search = $request->input('search');
        
            // Query bookings with pagination and filtering by search term and user-specific filter
            $bookings = Booking::query()
                ->when($search, function ($query, $search) {
                    $query->where('doc_name', 'like', "%{$search}%")
                        ->orWhere('admin_status', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                })
                ->where('user_id', $userid)  // Add the user-specific filter here
                ->latest()
                ->paginate(10); // Paginate with 10 bookings per page
        
            return inertia('patient/myappointment', [
                'bookings' => $bookings,
                'search' => $search,
                'headers' => $headers,
                'footers' => $footers,
            ]);
        } else {
            // Redirect back if the user is not authenticated
            return redirect()->back();
        }
    
    }

    
    // <!--patient here..--!>

    public function doctorDashboard(): Response
    {
        
        if(Auth::id())
        {
           
            $userid=Auth::user()->id;
            $scheduleCount=Schedule::where('user_id', $userid)->count();
            $bookCount=Booking::where('schedule_user_id', $userid)->count();
         
        return Inertia::render('doctor/dashboard', [
            'scheduleCount' => $scheduleCount,
            'bookCount' => $bookCount,
        ]);

        }
        
        else
        {
            return redirect()->back();
        }

    }

    public function doctorProfile(): Response
    {
        return Inertia::render('doctor/profile');
    }


    public function doctorAppointment(Request $request): Response
    {


        if (Auth::check()) {
            $userid = Auth::user()->id;
        
            // Get the search term from the request
            $search = $request->input('search');
        
            // Query bookings with pagination and filtering by search term and user-specific filter
            $bookings = Booking::query()
                ->when($search, function ($query, $search) {
                    $query->where('patient_name', 'like', "%{$search}%")
                        ->orWhere('admin_status', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                })
                ->where('schedule_user_id', $userid)  // Add the user-specific filter here
                ->latest()
                ->paginate(10); // Paginate with 10 bookings per page
        
            return inertia('doctor/appointment', [
                'bookings' => $bookings,
                'search' => $search,
            ]);
        } else {
            // Redirect back if the user is not authenticated
            return redirect()->back();
        }
        
    }




    public function doctorSchedule(Request $request): Response
    {
        if (Auth::check()) {
            $userid = Auth::user()->id;

            // Get the search term from the request
            $search = $request->input('search');

            // Fetch schedules for the logged-in doctor (user_id = $userid)
            $schedules = Schedule::query()
                ->where('user_id', $userid) // Only fetch schedules for the authenticated doctor
                ->when($search, function ($query, $search) {
                    $query->where('start_date', 'like', "%{$search}%")
                        ->orWhere('end_time', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10); // Paginate with 10 schedules per page

            // Return the schedules to the Inertia view
            return inertia('doctor/schedule', [
                'schedules' => $schedules,
                'search' => $search,
            ]);
        } else {
            // Redirect back if the user is not authenticated
            return redirect()->back();
        }
    }




    public function showByDepartment($departmentId)
    {
        $headers = Header::all();
        $footers = Footer::all();
        // Fetch the department by its ID
        $department = Department::findOrFail($departmentId);

        // Fetch doctors where 'docdep' matches the department's 'titledep'
        $doctors = Doctor::where('docdep', $department->titledep)->get();

        // Return data to Inertia
        return Inertia::render('patient/dept_doc', [
            'department' => $department,
            'doctors' => $doctors,
            'headers' => $headers,
            'footers' => $footers,
        ]);
    }



    public function showDoctor(Request $request, $docname)
    {
        $headers = Header::all();
        $footers = Footer::all();

        $doctor = Doctor::where('docname', $docname)->firstOrFail();

        // Get the search term from the request
        $search = $request->input('search');

        // Fetch schedules where the doctor's name matches the docname in the schedules table
        $schedules = Schedule::where('docname', $doctor->docname)
            ->when($search, function ($query, $search) {
                $query->where('start_date', 'like', "%{$search}%")
                    ->orWhere('end_time', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10); // Paginate with 10 schedules per page

        return Inertia::render('DoctorProfile', [
            'search' => $search,
            'doctor' => $doctor,
            'schedules' => $schedules,
            'headers' => $headers,
            'footers' => $footers,
        ]);
    }
}
