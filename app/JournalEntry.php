<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    //
    public function journals() {
        return $this->belongsTo("App\Journal");
    }

    public function versions() {
        return $this->hasMany("App\Version","entry_id");
    }

    public function keywords() {
        return $this->belongsToMany("App\Keyword");
    }
    
}
