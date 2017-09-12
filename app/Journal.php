<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    //

    public function user() {
        return $this->belongsTo("App\User");
    }

    public function journal_entries() {
        return $this->hasMany("App\JournalEntry");
    }
}
