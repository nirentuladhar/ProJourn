<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
class JournalsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function store() {

        $this->validate(request(), [
            'name' => 'required'
        ]);

        \App\Journal::create([
            'user_id' => auth()->user()->id,
            'name' => request('name')
        ]);

        $x = fetchJournals();

        return ['message' => 'Journal created!'];
    }


    public function fetchJournals() {
        return auth()->user()->journals;
    }



    public function index() {
        $user = auth()->user();
        $journals = $user->journals;


        $j = \App\Journal::first();
        $jes = $j->journal_entries; //list of journal entries
        $jeje = $jes->first(); //first journal entry
        $jev = $jeje->latestVersions->first(); //latest journal version
        $result = array();
        foreach($jes as $je) {
            foreach($je->versions as $ver) {
                array_push($result,$ver);
            }
        }

        $journal_1 = \App\Journal::first();
        $journal_entries = $journal_1->journal_entries;
        

        return view('journals', compact('journals','result','jev', 'journal_entries'));
    }
}

//if journal click, load journal entry.
//if journal_entry click, load latest version.
