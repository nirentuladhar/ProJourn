@extends('public.master.layout')

@section('main-content')

<div class="grid-container full home-container" id="app">
    <div class="grid-x grid-padding-x home-wrapper">
        <div class="cell small-12 medium-4">
            <div class="home-form">
                <h1>Sign in</h1>
                <form method="POST" action="/login">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="button">Sign in</button>
                    </div>

                </form>
                <p>Don’t have an account? <a href="/register">Register!</a></p>
            </div>
        </div>
    </div>
</div>


@endsection