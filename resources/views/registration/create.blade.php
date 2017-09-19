@extends('public.master.layout')

@section('main-content')

<div class="grid-container full home-container" id="app">
    <div class="grid-x grid-padding-x home-wrapper">
        <div class="cell small-12 medium-4">
            <div class="home-form">
                <h1>Register</h1>
                <form method="POST" action="/register">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="firstname">First Name:</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name:</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" required>
                    </div>
                    <div class="form-group">
                        <label for="jobtitle">Job Title:</label>
                        <input type="text" class="form-control" id="jobtitle" name="jobtitle" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password:</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    @include ('layouts.errors')
                    <div class="form-group">
                        <button type="submit" class="button">Register</button>
                    </div>


                </form>
                <p>Already have an account? <a href="/login">Sign in!</a></p>
            </div>
        </div>
    </div>
</div>


@endsection

