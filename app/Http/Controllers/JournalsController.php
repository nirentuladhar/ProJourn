<?php

namespace App\Http\Controllers;

use DB;
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
        $request->deletedFlag = $this->convertToBinary($request->deletedFlag);
        $request->hiddenFlag = $this->convertToBinary($request->hiddenFlag);

        

        if ($request->hiddenFlag == '0' && $request->deletedFlag == '0') {
            if ($request->date_from != "") {
                return $this->searchRegularEntriesWithDate($request);
            }
            return $this->searchRegularEntries($request);
        } else {
            if ($request->date_from != "") { 
                return $this->searchIncludesHiddenDeletedEntriesWithDate($request);
            }
            return $this->searchIncludesHiddenDeletedEntries($request);
        }   
    }




    public function convertToBinary($request) {
        if ($request == 'true') {
            return '1';
        } else {
            return '0';
        }; 
    }


    public function searchRegularEntries($request) {
        $user_id = auth()->user()->id;
        $all = auth()->user()
            ->join('journals', $user_id, '=', 'journals.user_id')
            ->join('journal_entries','journals.id', '=', 'journal_entries.journal_id')
            ->join('versions', 'journal_entries.id', '=', 'versions.entry_id')
            ->select(
                DB::raw('journals.id AS journal_id'),
                DB::raw('journals.name AS journal_name'),
                'versions.id',
                'versions.entry_id',
                'versions.title',
                'versions.body',
                'versions.created_at',
                'versions.updated_at'
                )
            ->where('users.id', '=', $user_id)
            ->where(function($query) use ($request){
                $query
                ->where('versions.title', 'LIKE', "%$request->searchTerm%")
                ->orWhere('versions.body', 'LIKE', "%$request->searchTerm%");
            })
            ->where(function($query) use ($request){
                $query->where('journal_entries.deleted', '=', $request->deletedFlag);
            })
            ->where(function($query) use ($request){
                $query->where('journal_entries.hidden', '=', $request->hiddenFlag);
            })
            ->get();

        return $all;
    }

    
    public function searchRegularEntriesWithDate($request) {
        $user_id = auth()->user()->id;
        $variable = !$this->checkValidDate($request->date_upto);
        $all = auth()->user()
            ->join('journals', $user_id, '=', 'journals.user_id')
            ->join('journal_entries','journals.id', '=', 'journal_entries.journal_id')
            ->join('versions', 'journal_entries.id', '=', 'versions.entry_id')
            ->select(
                DB::raw('journals.id AS journal_id'),
                DB::raw('journals.name AS journal_name'),
                'versions.id',
                'versions.entry_id',
                'versions.title',
                'versions.body',
                'versions.created_at',
                'versions.updated_at',
                'journal_entries.deleted'
                )
            ->where('users.id', '=', $user_id)
            ->where(function($query) use ($request){
                $query
                ->where('versions.title', 'LIKE', "%$request->searchTerm%")
                ->orWhere('versions.body', 'LIKE', "%$request->searchTerm%");
            })
            ->where(function($query) use ($request){
                $query->where('journal_entries.deleted', '=', $request->deletedFlag);
            })
            ->where(function($query) use ($request){
                $query->where('journal_entries.hidden', '=', $request->hiddenFlag);
            })
            ->when($variable, function($query) use ($request) {
                return $query->whereDate('versions.updated_at', '=', $request->date_from);
            }, function ($query) use ($request) {
                return $query->whereDate('versions.updated_at', '>=', $request->date_from)
                             ->whereDate('versions.updated_at', '<=', $request->date_upto);
            });
        return $all->get();
    }

    public function checkValidDate($request) {
        return $request != "";

    }


    public function searchIncludesHiddenDeletedEntries($request) {
        $user_id = auth()->user()->id;   
        $all = auth()->user()
            ->join('journals', $user_id, '=', 'journals.user_id')
            ->join('journal_entries','journals.id', '=', 'journal_entries.journal_id')
            ->join('versions', 'journal_entries.id', '=', 'versions.entry_id')
            ->select(
                DB::raw('journals.id AS journal_id'),
                DB::raw('journals.name AS journal_name'),
                'versions.id',
                'versions.entry_id',
                'versions.title',
                'versions.body',
                'versions.created_at',
                'versions.updated_at',
                'journal_entries.deleted'
                )
            ->where('users.id', '=', $user_id)
            ->where(function($query) use ($request){
                $query
                ->where('versions.title', 'LIKE', "%$request->searchTerm%")
                ->orWhere('versions.body', 'LIKE', "%$request->searchTerm%")
                ->where('journal_entries.deleted', '=', $request->deletedFlag)
                ->where('journal_entries.hidden', '=', $request->hiddenFlag);
            })
            ->get();

        return $all;
    }

    public function searchIncludesHiddenDeletedEntriesWithDate($request) {
        $user_id = auth()->user()->id;  
        $variable = !$this->checkValidDate($request->date_upto); 
        $all = auth()->user()
            ->join('journals', $user_id, '=', 'journals.user_id')
            ->join('journal_entries','journals.id', '=', 'journal_entries.journal_id')
            ->join('versions', 'journal_entries.id', '=', 'versions.entry_id')
            ->select(
                DB::raw('journals.id AS journal_id'),
                DB::raw('journals.name AS journal_name'),
                'versions.id',
                'versions.entry_id',
                'versions.title',
                'versions.body',
                'versions.created_at',
                'versions.updated_at',
                'journal_entries.deleted'
                )
            ->where('users.id', '=', $user_id)
            ->where(function($query) use ($request){
                $query
                ->where('versions.title', 'LIKE', "%$request->searchTerm%")
                ->orWhere('versions.body', 'LIKE', "%$request->searchTerm%")
                ->where('journal_entries.deleted', '=', $request->deletedFlag)
                ->where('journal_entries.hidden', '=', $request->hiddenFlag);
            })
            ->when($variable, function($query) use ($request) {
                return $query->whereDate('versions.updated_at', '=', $request->date_from);
            }, function ($query) use ($request) {
                return $query->whereDate('versions.updated_at', '>=', $request->date_from)
                             ->whereDate('versions.updated_at', '<=', $request->date_upto);
            });

        return $all->get();
    }

    
}
