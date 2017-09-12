<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntryKeywordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_keyword', function (Blueprint $table) {
            $table->integer('entry_id')->unsigned();
            $table->integer('keyword_id')->unsigned();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('entry_keyword', function (Blueprint $table) {
            $table->foreign('entry_id')->references('id')->on('journal_entries');
            $table->foreign('keyword_id')->references('id')->on('keywords');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entry_keyword');
    }
}
