<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    public function journalentry() {
        return $this->belongsTo("App\JournalEntry", "entry_id");
    }
    
}
