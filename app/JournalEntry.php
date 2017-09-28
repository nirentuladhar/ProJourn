<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    //
    protected $fillable = [
        'journal_id', 'hidden', 'deleted' , 'keywords',
    ];

    public function journals() {
        return $this->belongsTo("App\Journal");
    }

    public function versions() {
        return $this->hasMany("App\Version","entry_id");
    }

    public function keywords() {
        return $this->belongsToMany("App\Keyword");
    }
    
    public function latestVersions() {
        return $this->hasMany("App\Version","entry_id")->latest('updated_at');
    }
}
