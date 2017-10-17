<?php

// Home page
Route::get('/', 'JournalsController@index')->name('home');


// Login and registration
Route::get('/register', 'RegistrationController@create');
Route::post('/register', 'RegistrationController@store');
Route::get('/login', 'SessionsController@create')->name('login');
Route::post('/login', 'SessionsController@store');
Route::get('/logout', 'SessionsController@destroy');

//FAQs
Route::get('/faqs', 'FaqController@index');



// API -> fetch records from requests
Route::get('api/journals', 'JournalsController@fetchJournals');
Route::post('api/journalEntries', 'JournalsController@fetchJournalEntries');
Route::post('api/journalEntry', 'JournalsController@fetchJournalEntry');

Route::post('api/versions', 'JournalsController@fetchVersions');
Route::post('api/version', 'JournalsController@fetchVersion');

Route::post('api/hiddenEntries', 'JournalsController@fetchHiddenEntries');
Route::post('api/deletedEntries', 'JournalsController@fetchDeletedEntries');


// API -> create new records
Route::post('api/newJournal', 'JournalsController@store');
Route::post('api/newJournalEntry', 'JournalsController@storeEntries');
Route::post('api/newJournalEntryVersion', 'JournalsController@storeJournalEntryVersion');


// API -> update existing records
Route::post('api/toggleHideJournalEntry', 'JournalsController@toggleHideJournalEntry');
Route::post('api/deleteJournalEntry', 'JournalsController@deleteJournalEntry');

// API -> search for records
Route::post('api/searchAllEntries', 'JournalsController@searchAllEntries');
Route::get('api/searchAllEntries', 'JournalsController@searchAllEntries');


