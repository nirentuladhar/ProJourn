<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function __construct() {
        // Doesn't allow access if the user is already logged in
        $this->middleware('guest', ['except' => 'destroy']);
    }

    // Returns login page
    public function create() {
        return view('sessions.create');
    }

    public function destroy() {
        // Log out auth, redirect home after successful log out
        auth()->logout();
        return redirect()->home();
    }

    public function store() {
        // Attempt to authenticate the user
        // If so, sign them in
        // Auth automatically signs in
        if (! auth()->attempt(request(['email', 'password']))) {
            //If not, redirect back
            return back()->withErrors([
                'message' => 'Please check your credentials and try again.'
            ]);
        }
        // If successful login, redirect to the home page
        return redirect()->home();
    }


    public function faqs()
    {
        return view('sessions.create');
    }
}
