<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EpicSprintRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("epic_sprint_records", function($table) {
            $table->bigIncrements('id');
            $table->string("jira_key");
            $table->string("jira_id");
            $table->string("jira_type");
            $table->string("name");
            $table->string("summary");
            $table->string("done")->default(false);
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
