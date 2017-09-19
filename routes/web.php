<?php


Route::get('/', 'JournalsController@index')->name('home');


Route::get('/register', 'RegistrationController@create');
Route::post('/register', 'RegistrationController@store');

Route::get('/login', 'SessionsController@create')->name('login');
Route::post('/login', 'SessionsController@store');

Route::get('/logout', 'SessionsController@destroy');

// component will make a request to get this
Route::get('api/journals', function() {
    return App\Journal::latest()->get();
});


