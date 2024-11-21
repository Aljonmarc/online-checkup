<?php

namespace App\Http\Controllers;

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

class AdminController extends Controller
{



    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|min:10',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'role' => 'required|string', //edit here
            'password' => 'required',
        ]);


        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role, //edit here
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);


        return redirect()->route('admin.users');
    }



    public function updateUser(User $user)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'phone' => 'required',
            'address' => 'required',
            'role' => 'required',

        ]);


        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'phone' => request('phone'),
            'address' => request('address'),
            'role' => request('role'),
            'password' => request('password') ? bcrypt(request('password')) : $user->password,
        ]);



        return redirect()->route('admin.users');
    }

    public function destroyUser(User $user): RedirectResponse
    {

        $user->delete();


        return to_route('admin.users');
    }


    public function storeH(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'logo' => 'required',

        ]);

        $header = new Header;

        $logo = $request->logo;

        $imagename = time() . '.' . $logo->getClientOriginalExtension();

        $request->logo->move('headerlogo', $imagename);
        $header->logo = $imagename;
        $header->title = $request->title;


        $header->save();

        return redirect()->route('admin.settings');
    }


    public function updateH(Request $request, Header $header): RedirectResponse
    {
        // Validate the request inputs
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required',
        ]);

        // Update the header properties
        $header->title = $request->title;

        // Check if an image file is provided
        if ($request->hasFile('logo')) {
            // Delete the old logo if it exists
            if ($header->logo && file_exists(public_path('headerlogo/' . $header->logo))) {
                unlink(public_path('headerlogo/' . $header->logo));
            }

            // Handle logo file upload
            $logo = $request->file('logo');
            $imageName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('headerlogo'), $imageName);
            $header->logo = $imageName;
        }

        // Save the updated header
        $header->save();

        // Redirect back to the admin settings page with a success message
        return redirect()->route('admin.settings')->with('success', 'Header updated successfully!');
    }

    public function destroyH(Header $header): RedirectResponse
    {
        // Check if the logo exists and delete it from the directory
        if ($header->logo && file_exists(public_path('headerlogo/' . $header->logo))) {
            unlink(public_path('headerlogo/' . $header->logo));
        }

        // Delete the header record
        $header->delete();

        // Redirect back to the admin settings page with a success message
        return to_route('admin.settings')->with('success', 'Header and logo deleted successfully!');
    }


    public function storeF(Request $request)
    {

        $request->validate([
            'fbname' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'place' => 'required|string|max:255',
        ]);


        Footer::create([
            'fbname' => $request->fbname,
            'contact' => $request->contact,
            'place' => $request->place,
        ]);


        return redirect()->route('admin.settings');
    }

    public function updateF(Request $request, Footer $footer): RedirectResponse
    {
        // Validate the request inputs
        request()->validate([
            'fbname' => 'required',
            'contact' => 'required',
            'place' => 'required',


        ]);


        $footer->update([
            'fbname' => request('fbname'),
            'contact' => request('contact'),
            'place' => request('place'),

        ]);


        // Redirect back to the admin room page with a success message

        return redirect()->route('admin.settings');
    }

    public function destroyF(Footer $footer): RedirectResponse
    {

        $footer->delete();


        return to_route('admin.settings');
    }



    public function storeS(Request $request)
    {

        $request->validate([
            'stitle' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'description' => 'required',
        ]);

        $service = new Service;

        $image = $request->image;

        $imagename = time() . '.' . $image->getClientOriginalExtension();

        $request->image->move('serviceimage', $imagename);
        $service->image = $imagename;
        $service->stitle = $request->stitle;
        $service->description = $request->description;


        $service->save();

        return redirect()->route('admin.services');
    }


    public function updateS(Request $request, Service $service): RedirectResponse
    {

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stitle' => 'required',
            'description' => 'required',
        ]);


        $service->stitle = $request->stitle;
        $service->description = $request->description;


        // Check if an image file is provided
        if ($request->hasFile('image')) {

            // Delete the old logo if it exists
            if ($service->image && file_exists(public_path('serviceimage/' . $service->image))) {
                unlink(public_path('serviceimage/' . $service->image));
            }
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('serviceimage'), $imageName);
            $service->image = $imageName;
        }




        $service->save();

        return redirect()->route('admin.services');
    }

    public function destroyS(Service $service): RedirectResponse
    {

        // Check if the image exists and delete it from the directory
        if ($service->image && file_exists(public_path('serviceimage/' . $service->image))) {
            unlink(public_path('serviceimage/' . $service->image));
        }

        $service->delete();


        return to_route('admin.services');
    }

    public function storeDep(Request $request)
    {

        $request->validate([
            'titledep' => 'required',
            'imagedep' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        $department = new Department;

        $imagedep = $request->imagedep;

        $imagename = time() . '.' . $imagedep->getClientOriginalExtension();

        $request->imagedep->move('departmentimage', $imagename);
        $department->imagedep = $imagename;
        $department->titledep = $request->titledep;



        $department->save();

        return redirect()->route('admin.departments');
    }

    public function updateDep(Request $request, Department $department): RedirectResponse
    {

        $request->validate([
            'imagedep' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'titledep' => 'required',

        ]);


        $department->titledep = $request->titledep;



        // Check if an image file is provided
        if ($request->hasFile('imagedep')) {

            // Delete the old logo if it exists
            if ($department->imagedep && file_exists(public_path('departmentimage/' . $department->imagedep))) {
                unlink(public_path('departmentimage/' . $department->imagedep));
            }

            $imagedep = $request->file('imagedep');
            $imageName = time() . '.' . $imagedep->getClientOriginalExtension();
            $imagedep->move(public_path('departmentimage'), $imageName);
            $department->imagedep = $imageName;
        }


        $department->save();

        return redirect()->route('admin.departments');
    }

    public function destroyDep(Department $department): RedirectResponse
    {

        if ($department->imagedep && file_exists(public_path('departmentimage/' . $department->imagedep))) {
            unlink(public_path('departmentimage/' . $department->imagedep));
        }

        $department->delete();


        return to_route('admin.departments');
    }


    public function storeDoc(Request $request)
    {

        $request->validate([
            'docimage' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'docroom' => 'required',
            'docname' => 'required|unique:doctors,docname',
            'docspec' => 'required',
            'docphone' => 'required',
            'docdep' => 'required',
        ]);

        

        $doctor = new Doctor;

        $docimage = $request->docimage;

        $imagename = time() . '.' . $docimage->getClientOriginalExtension();

        $request->docimage->move('doctorimage', $imagename);
        $doctor->docimage = $imagename;

        $doctor->docroom = $request->docroom;
        $doctor->docname = $request->docname;
        $doctor->docspec = $request->docspec;
        $doctor->docphone = $request->docphone;
        $doctor->docdep = $request->docdep;

        $doctor->save();

        return redirect()->route('admin.doctors');
    }

    public function updateDoc(Request $request, Doctor $doctor): RedirectResponse
    {

        $request->validate([
            'docimage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'docroom' => 'required',
            'docname' => 'required',
            'docspec' => 'required',
            'docphone' => 'required',
            'docdep' => 'required',

        ]);


        $doctor->docroom = $request->docroom;
        $doctor->docname = $request->docname;
        $doctor->docspec = $request->docspec;
        $doctor->docphone = $request->docphone;
        $doctor->docdep = $request->docdep;

        // Check if an image file is provided
        if ($request->hasFile('docimage')) {

            // Delete the old logo if it exists
            if ($doctor->docimage && file_exists(public_path('doctorimage/' . $doctor->docimage))) {
                unlink(public_path('doctorimage/' . $doctor->docimage));
            }

            $docimage = $request->file('docimage');
            $imageName = time() . '.' . $docimage->getClientOriginalExtension();
            $docimage->move(public_path('doctorimage'), $imageName);
            $doctor->docimage = $imageName;
        }


        $doctor->save();

        return redirect()->route('admin.doctors');
    }

    public function destroyDoc(Doctor $doctor): RedirectResponse
    {

        if ($doctor->docimage && file_exists(public_path('doctorimage/' . $doctor->docimage))) {
            unlink(public_path('doctorimage/' . $doctor->docimage));
        }

        $doctor->delete();


        return to_route('admin.doctors');
    }





    public function storeRes(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'imagedep' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
        ]);

        $research_lab = new Research_lab();

        $image = $request->image;

        $imagename = time() . '.' . $image->getClientOriginalExtension();

        $request->image->move('research_labimage', $imagename);
        $research_lab->image = $imagename;
        $research_lab->name = $request->name;

        $research_lab->description = $request->description;



        $research_lab->save();

        return redirect()->route('admin.research_labs');
    }


    public function updateRes(Request $request, Research_lab $research_lab): RedirectResponse
    {

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'description' => 'required',
        ]);


        $research_lab->name = $request->name;
        $research_lab->description = $request->description;


        // Check if an image file is provided
        if ($request->hasFile('image')) {

            // Delete the old logo if it exists
            if ($research_lab->image && file_exists(public_path('research_labimage/' . $research_lab->image))) {
                unlink(public_path('research_labimage/' . $research_lab->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('research_labimage'), $imageName);
            $research_lab->image = $imageName;
        }


        $research_lab->save();

        return redirect()->route('admin.research_labs');
    }


    public function destroyRes(Research_lab $research_lab): RedirectResponse
    {

        if ($research_lab->image && file_exists(public_path('research_labimage/' . $research_lab->image))) {
            unlink(public_path('research_labimage/' . $research_lab->image));
        }

        $research_lab->delete();


        return to_route('admin.research_labs');
    }




    public function storeAward(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:2099',
            'description' => 'required|string|max:255',
        ]);


        Award::create([
            'title' => $request->title,
            'year' => $request->year,
            'description' => $request->description,
        ]);


        return redirect()->route('admin.awards');
    }



    public function updateAward(Request $request, Award $award): RedirectResponse
    {
        // Validate the request inputs
        request()->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:2099',
            'description' => 'required|string|max:255',

        ]);


        $award->update([
            'title' => request('title'),
            'year' => request('year'),
            'description' => request('description'),

        ]);


        // Redirect back to the admin room page with a success message

        return redirect()->route('admin.awards');
    }

    public function destroyAward(Award $award): RedirectResponse
    {

        $award->delete();


        return to_route('admin.awards');
    }


    public function storeRoom(Request $request)
    {

        $request->validate([
            'room_number' => 'required|max:50|unique:rooms,room_number',
     
        ]);


        Room::create([
            'room_number' => $request->room_number,
       
        ]);


        return redirect()->route('admin.rooms');
    }

    public function updateRoom(Request $request, Room $room): RedirectResponse
    {
        // Validate the request inputs
        request()->validate([
            'room_number' => 'required|max:50',

        ]);


        $room->update([
            'room_number' => request('room_number'),
    
        ]);


        // Redirect back to the admin room page with a success message

        return redirect()->route('admin.rooms');
    }

    public function destroyRoom(Room $room): RedirectResponse
    {

        $room->delete();


        return to_route('admin.rooms');
    }



    public function storeSpec(Request $request)
    {

        $request->validate([
    
            'name' => 'required|string|max:255|unique:specialties,name',
        ]);


        Specialty::create([
            'name' => $request->name,
       
        ]);


        return redirect()->route('admin.specialtys');
    }

    public function updateSpec(Request $request, Specialty $specialty): RedirectResponse
    {
        // Validate the request inputs
        request()->validate([
            'name' => 'required|string|max:255',

        ]);


        $specialty->update([
            'name' => request('name'),
    
        ]);


        // Redirect back to the admin room page with a success message

        return redirect()->route('admin.specialtys');
    }

    public function destroySpec(Specialty $specialty): RedirectResponse
    {

        $specialty->delete();


        return to_route('admin.specialtys');
    }


}
