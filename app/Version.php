<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{

    protected $fillable = [
        'entry_id', 'title' , 'body',
    ];

    public function journalentry() {
        return $this->belongsTo("App\JournalEntry", "entry_id")->orderBy('updated_at','DESC');
    }

}