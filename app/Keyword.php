<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    //
    public function journalentries() {
        return $this->belongsToMany("App\JournalEntry");
    }
}
