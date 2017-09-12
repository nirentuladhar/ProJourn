<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class RegistrationController extends Controller
{
    public function create() {
        return view('registration.create');
    }
    public function store() {
        // Validate the form
        $this->validate(request(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'jobtitle' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed',
        ]);

        // Create and save the user 'name' => request('name'),

        $user = User::create([
            'firstname' => request('firstname'),
            'lastname' => request('lastname'),
            'email' => request('email'),
            'jobtitle' => request('jobtitle'),
            'password' =>bcrypt(request('password'))
        ]);

        // Sign them in
        auth()->login($user);

        // Redirect to the home page
        return redirect()->home();
    }
}
