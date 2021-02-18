<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOneDriveTokenManagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('one_drive_token_manages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')->nullable();
            $table->text('accessToken');
            $table->text('refreshToken');
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
        Schema::dropIfExists('one_drive_token_manages');
    }
}
