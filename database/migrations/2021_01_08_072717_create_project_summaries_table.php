<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_summaries', function (Blueprint $table) {
            $table->increments("id");
            $table->string("JobNumber");
            $table->string("Suffix");
            $table->string("ReleaseNumber");
            $table->string("Released");
            $table->string("Produced");
            $table->string("Staged");
            $table->string("Shipped");
            $table->string("ReleasedValue");
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
        Schema::dropIfExists('project_summaries');
    }
}
