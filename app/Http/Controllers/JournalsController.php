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

        return ['message' => 'Journal created!'];
    }

    public function storeJournalEntryVersion() {
        \App\Version::create([
            'entry_id' => request('entry_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);
        return ['message' => 'Entry version created!'];
    }

    public function storeEntries() {

        $this->validate(request(), [

        ]);
        \App\JournalEntry::create([

        ]);
        \App\Version::create([

        ]);
        return 0;

    }


    public function fetchJournals() {
        return auth()->user()->journals;
    }
    
    public function fetchJournalEntries(Request $request) {
        $journal_entries = auth()->user()->journals->find($request->journal_id)->journal_entries;
        $all_entries = array();
        foreach($journal_entries as $journal_entry) {
            if($journal_entry->hidden == 0 && $journal_entry->deleted == 0) {
                foreach($journal_entry->latestVersions as $versions) {
                    array_push($all_entries, $versions);
                    break;
                }
            }
        }
        return $all_entries;
    }


    public function fetchHiddenEntries(Request $request) {
        $journal_entries = auth()->user()->journals->find($request->journal_id)->journal_entries;
        // return $journal_entries[0]->hidden;
        $all_hidden_entries = array();
        foreach($journal_entries as $journal_entry) {
            if($journal_entry->hidden == 1) {
                foreach($journal_entry->latestVersions as $versions) {
                    array_push($all_hidden_entries, $versions);
                    break;
                }
            }
        }
        return $all_hidden_entries;
    }

    public function fetchDeletedEntries(Request $request) {
        $journal_entries = auth()->user()->journals->find($request->journal_id)->journal_entries;
        // return $journal_entries[0]->hidden;
        $all_deleted_entries = array();
        foreach($journal_entries as $journal_entry) {
            if($journal_entry->deleted == 1) {
                foreach($journal_entry->latestVersions as $versions) {
                    array_push($all_deleted_entries, $versions);
                    break;
                }
            }
        }
        return $all_deleted_entries;
    }

    public function toggleHideJournalEntry(Request $request) {
        $journal_entries = auth()->user()->journals->find($request->journal_id)->journal_entries->find($request->id);
        $journal_entries->hidden = ($journal_entries->hidden == 1) ? 0 : 1;
        $journal_entries->save();
        return $journal_entries;
    }


    public function deleteJournalEntry(Request $request) {
        $journal_entries = auth()->user()->journals->find($request->journal_id)->journal_entries->find($request->id);
        $journal_entries->deleted = 1;
        $journal_entries->save();
        return 'deleted';
    }



    public function fetchJournalEntry(Request $request) {
        $journal_entry = auth()->user()->journals->find($request->journal_id)->journal_entries->find($request->entry_id)->latestVersions->first();
        return $journal_entry;
    }

    public function fetchVersions(Request $request) {
        $versions = auth()->user()->journals->find($request->journal_id)->journal_entries->find($request->entry_id)->latestVersions;
        return $versions;
    }

    public function fetchVersion(Request $request) {
        $version = auth()->user()->journals->find($request->journal_id)->journal_entries->find($request->entry_id)->latestVersions->find($request->id);
        return $version;
    }

    



    public function index() {
        $user = auth()->user();
        $journals = $user->journals;


        // $j = \App\Journal::first();
        // $jes = $j->journal_entries; //list of journal entries
        // $jeje = $jes->first(); //first journal entry
        // $jev = $jeje->latestVersions->first(); //latest journal version
        // $result = array();
        // foreach($jes as $je) {
        //     foreach($je->versions as $ver) {
        //         array_push($result,$ver);
        //     }
        // }

        // $journal_1 = \App\Journal::first();
        // $journal_entries = $journal_1->journal_entries;
        

        // return view('journals', compact('journals','result','jev', 'journal_entries'));
        return view('journals', compact('journals'));
    }
}

//if journal click, load journal entry.
//if journal_entry click, load latest version.
