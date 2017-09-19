<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function __construct() {
        // Doesn't allow access if the user is already logged in
        $this->middleware('guest', ['except' => 'destroy']);
    }

    public function create() {
        return view('sessions.create');
    }

    public function destroy() {
        auth()->logout();
        return redirect()->home();
    }

    public function store() {
        // Attempt to authenticate the user
        // If so, sign them in
        // Auth automatically signs in
        if (! auth()->attempt(request(['email', 'password']))) {
            //If not, redirect back
            return back();
        }
        // After login, Redirect to the home page
        return redirect()->home();
    }
}
