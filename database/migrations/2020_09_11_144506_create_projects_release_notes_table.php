<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsReleaseNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects_release_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('projects_release_notes');
            $table->integer('projects_id')->nullable();
            $table->bigInteger('pm_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('projects_release_id');
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
        Schema::dropIfExists('projects_release_notes');
    }
}
