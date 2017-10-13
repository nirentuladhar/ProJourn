<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
class JournalsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    // Gets values from the user through a request 
    // Creates a new journal
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

    // Gets values from the user through a request 
    // Creates a new version for a journal entry
    public function storeJournalEntryVersion() {
        $this->validate(request(), [
            'title' => 'required'
        ]);
        \App\Version::create([
            'entry_id' => request('entry_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);
        return ['message' => 'Entry version created!'];
    }

    // Gets values from the user through a request 
    // Creates a new journal entry
    public function storeEntries() {
        $this->validate(request(), [
            'journal_id' => 'required',
            'title' => 'required'
        ]);   
        // Create a new entry record
        $journalentry = new \App\JournalEntry();
        $journalentry->journal_id = request('journal_id');
        $journalentry->hidden = 0;
        $journalentry->deleted = 0;
        $journalentry->keywords = '';
        $journalentry->save();
        // Create a new version record
        \App\Version::create([
            'entry_id' => $journalentry->id,
            'title' => request('title'),
            'body' => ''
        ]);
        return ['message' => 'Entry created!'];;

    }

    // Returns all the journals associated with the user
    public function fetchJournals() {
        return auth()->user()->journals;
    }
    
    // Returns last modified version of all the entries of the requested journal associated with the logged in user
    // Doesn't return hidden or deleted entries
    public function fetchJournalEntries(Request $request) {
        // Find all the entries of requested journal
        $journal_entries = auth()->user()->journals->find($request->journal_id)->journal_entries;
        $all_entries = array();
        // Get the last modified version of the entry
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

    // Returns last modified version of all the HIDDEN entries of the requested journal associated with the logged in user
    public function fetchHiddenEntries(Request $request) {
        $journal_entries = auth()->user()->journals->find($request->journal_id)->journal_entries;
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

    // Returns last modified version of all the DELETED entries of the requested journal associated with the logged in user
    public function fetchDeletedEntries(Request $request) {
        $journal_entries = auth()->user()->journals->find($request->journal_id)->journal_entries;
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

    // Returns last modified version of ALL the entries of the requested journal associated with the logged in user
    public function fetchJournalEntry(Request $request) {
        $journal_entry = auth()->user()->journals->find($request->journal_id)->journal_entries->find($request->entry_id)->latestVersions->first();
        return $journal_entry;
    }

    // Returns all versions of the requested journal entry
    public function fetchVersions(Request $request) {
        $versions = auth()->user()->journals->find($request->journal_id)->journal_entries->find($request->entry_id)->latestVersions;
        return $versions;
    }
    
    // Returns a version of the requested journal entry
    public function fetchVersion(Request $request) {
        $version = auth()->user()->journals->find($request->journal_id)->journal_entries->find($request->entry_id)->latestVersions->find($request->id);
        return $version;
    }
    
    // Hides or unhides a journal entry
    public function toggleHideJournalEntry(Request $request) {
        $journal_entries = auth()->user()->journals->find($request->journal_id)->journal_entries->find($request->id);
        $journal_entries->hidden = ($journal_entries->hidden == 1) ? 0 : 1;
        $journal_entries->save();
        return $journal_entries;
    }

    // Deletes a journal entry
    public function deleteJournalEntry(Request $request) {
        $journal_entries = auth()->user()->journals->find($request->journal_id)->journal_entries->find($request->id);
        $journal_entries->deleted = 1;
        $journal_entries->save();
        return 'deleted';
    }


    public function index() {
        $user = auth()->user();
        $journals = $user->journals;
        return view('journals', compact('journals'));
    }
    
    
    
    public function searchAllEntries(Request $request) {
        // $matchedTerm = Breed::where('text', 'LIKE', '%' . $term . '%')->get();

        // $versions = auth()->user()->journals->find($request->journal_id)->journal_entries->find($request->entry_id)->latestVersions;
        $all = array();
        //  $query->where('category', 'LIKE', '%' . $category . '%');
        $journals = auth()->user()->journals;
        foreach($journals as $journal) {
            foreach($journal->journal_entries as $entries) {
                foreach($entries->latestVersions as $versions) {
                    // array_push( $all,
                    //     $versions->where('body', 'LIKE', '%' . $request->searchTerm . '%')
                    //             ->orWhere('title', 'LIKE', '%' . $request->searchTerm . '%')
                    //             ->get()       
                    // );
                    array_push($all,
                        $versions->where(
                            'title', 'LIKE', "%$request->searchTerm%"
                        )->orWhere(
                            'body','LIKE',"%$request->searchTerm%"
                        )->orWhere(
                            ''
                        )->get()
                    );
                    break;
                }
                break;
            }
            break;
        }
        // return response()->json($matchedTerm);
        return $all;
    }
}

