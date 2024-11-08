<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Show the registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string|unique:user_details,username|min:3',
            'mobile' => 'nullable|digits_between:10,15',
            'gender' => 'required|in:male,female,other',
            'photo' => 'nullable|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // $request = $request->all();
        // Create the user
        $user = User::create([

            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        if ($request->hasFile('photo')) {
            // Get the uploaded file
            $photo = $request->file('photo');

            // Define a custom name or use the original file name
            $photoName = time() . '_' . $photo->getClientOriginalName();

            // Save the file to the 'public/photos' directory (use storage_path() or public_path() as needed)
            $photo->move(public_path('photos'), $photoName);

            // Store the file name or path in the database
            $user->photo = 'photos/' . $photoName;
        }

        // Create the user detail
        UserDetail::create([
            'user_id' => $user->id,
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'username' => $request['username'],
            'mobile' => $request['mobile'],
            'gender' => $request['gender'],
            'photo' => $user->photo ?? null,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. You can now login.');
    }
}
