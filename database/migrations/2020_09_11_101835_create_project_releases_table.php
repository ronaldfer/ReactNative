<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectReleasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_releases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('one_drive_projects_release_id')->nullable();
            $table->string('project_release_ver');
            $table->string('release_url_link')->nullable();
            $table->integer('projects_id')->nullable();
            $table->string('pm_id')->nullable();
            $table->integer('company_id')->nullable();
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
        Schema::dropIfExists('project_releases');
    }
}
