<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\Service;
use App\Models\Header;
use App\Models\Footer;
use App\Http\Controllers\Controller;
use App\Models\Timing;
use App\Models\Appointment;
use App\Models\Award;
use App\Models\Research_lab;
use App\Models\Room;
use App\Models\User;
use App\Models\Setting;
use App\Models\Specialty;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;


use Illuminate\Support\Facades\Redirect;

class DoctorController extends Controller
{
    public function saveSchedule(Request $request)
    {
       

        $request->validate([
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:available,already taken',
        ]);

        $data = new Schedule();
        $data->start_date = $request->start_date;
        $data->start_time = $request->start_time;
        $data->end_time = $request->end_time;
        $data->status = $request->status;

        if (Auth::id()) {
            $user = Auth::user(); // Retrieve the authenticated user
        
            $data->user_id = $user->id; // Store the authenticated user's ID
            $data->docname = $user->name; // Store the authenticated user's docname
        }

        $data->save();


        return redirect()->route('doctor.schedule');
    }


    public function updateSched(Request $request, Schedule $schedule)
{
    // Validate incoming data
    $request->validate([
        'start_date' => 'required|date',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'status' => 'required|in:available,already taken',
    ]);

 

    // Check if the authenticated user is allowed to update this schedule (for security)
    if ($schedule->user_id != Auth::id()) {
        return redirect()->route('doctor.schedule')->with('error', 'You are not authorized to update this schedule.');
    }

    // Update the schedule fields
    $schedule->start_date = $request->start_date;
    $schedule->start_time = $request->start_time;
    $schedule->end_time = $request->end_time;
    $schedule->status = $request->status;

    // Save the updated schedule
    $schedule->save();

    // Return an Inertia response
    return redirect()->route('doctor.schedule')->with('success', 'Schedule updated successfully.');
}


public function destroySched(Schedule $schedule): RedirectResponse
{

    $schedule->delete();


    return to_route('doctor.schedule');
}


public function updateAppointment(Request $request, $id)
{
    $booking = Booking::findOrFail($id);

    $validated = $request->validate([
        'admin_status' => 'required|string',
        'reply' => 'nullable|string|max:255',
    ]);

    $booking->update([
        'admin_status' => $validated['admin_status'],
        'reply' => $validated['reply'],
    ]);

    return back()->with('success', 'Booking updated successfully.');
}


}
