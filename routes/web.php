<?php


Route::get('/', 'JournalsController@index')->name('home');
Route::get('api/journals', 'JournalsController@fetchJournals');
Route::post('api/newJournal', 'JournalsController@store');
Route::post('api/newJournalEntry', 'JournalsController@store');


Route::get('api/journalEntries', 'JournalsController@fetchJournalEntries');




Route::get('/register', 'RegistrationController@create');
Route::post('/register', 'RegistrationController@store');

Route::get('/login', 'SessionsController@create')->name('login');
Route::post('/login', 'SessionsController@store');

Route::get('/logout', 'SessionsController@destroy');





//PASS JOURNAL ID
//RETURN ALL JOURNAL ENTRIES->LATEST VERSION ASSOCIATED WITH THE JOURNAL ID
Route::get('api/journal_entries', function() {
    $user = auth()->user();
    $journals = $user->journals;
    return '';
});


