<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('one_drive_projects_plan_id')->nullable();
            $table->string('project_plan_ver');
            $table->string('plan_url_link')->nullable();
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
        Schema::dropIfExists('projects_plans');
    }
}
