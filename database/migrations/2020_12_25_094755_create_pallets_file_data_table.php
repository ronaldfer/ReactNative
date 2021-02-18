<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePalletsFileDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pallets_file_data', function (Blueprint $table) {
            $table->id();
            $table->string("JobNumber");
            $table->string("Suffix")->nullable();
            $table->string("ReleaseNumber")->nullable();
            $table->string("PalletNumber")->nullable();
            $table->string("StoneID")->nullable();
            $table->string("Produced")->nullable();
            $table->string("ShipmentDate")->nullable();
            $table->string("StagingDate")->nullable();
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
        Schema::dropIfExists('pallets_file_data');
    }
}
