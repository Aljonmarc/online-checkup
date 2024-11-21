<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class PatientController extends Controller
{
    // Display testimonials submitted by patients
    public function testimonials()
    {
        $testimonials = Testimonial::where('role', 'patient')->latest()->get();

        return view('patient.testimonials', compact('testimonials'));
    }


    public function submitFeedback(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);


        // Create the testimonial
        Testimonial::create([
            'user_id' => Auth::id(), // Get the authenticated user's ID
            'message' => $request->message,
        ]);

        return back()->with('success', 'Testimonial submitted successfully.');
    }

    public function bookSchedule(Request $request)
    {
        // Validate the request
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
        ]);

        // Find the schedule by ID
        $schedule = Schedule::findOrFail($request->schedule_id);

        // Check if the schedule is available
        if ($schedule->status !== 'available') {
            return response()->json(['message' => 'Schedule is already taken'], 400);
        }

        // Retrieve the authenticated user (the patient booking the appointment)
        $user = Auth::user();

        // Create a new booking entry
        $booking = new Booking();
        $booking->schedule_id = $schedule->id;
        $booking->user_id = $user->id;
        $booking->patient_name = $user->name;
        $booking->patient_phone = $user->phone;
        $booking->status = 'booked';


        // Add additional fields for admin approval or cancellation
        $booking->admin_status = 'pending';  // Could be 'pending', 'approved', 'canceled'

        // Add the user_id from the schedule (the doctor/user assigned to the schedule)
        $booking->schedule_user_id = $schedule->user_id;  // Assuming `user_id` is the doctor or admin's ID on the schedule

        // Add the additional schedule details to the booking
        $booking->start_date = $schedule->start_date;  // Assuming the schedule has a start_date
        $booking->start_time = $schedule->start_time;  // Assuming the schedule has a start_time
        $booking->end_time = $schedule->end_time;      // Assuming the schedule has an end_time
        $booking->doc_name = $schedule->docname;  // Assuming the schedule has a relationship with the doctor (e.g., `doctor`)
        $booking->save();

        // Update the schedule status to 'already taken'
        $schedule->status = 'already taken';
        $schedule->save();

        // Return a success response
        return response()->json(['message' => 'Appointment successfully booked!']);
    }



    public function destroyBooking(Booking $booking): RedirectResponse
    {
        // Find the related schedule and update its status to "available"
        $schedule = Schedule::find($booking->schedule_id); // Assuming `schedule_id` is a foreign key in `bookings` table
    
        if ($schedule) {
            $schedule->status = 'available'; // Update the status
            $schedule->save();
        }
    
        // Delete the booking
        $booking->delete();
        

    
        // Redirect to the patient's appointment list with a success message
        return to_route('patient.myappointment');
    }


    
    
}
