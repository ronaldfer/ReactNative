<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerPortalMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_portal_mains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('JobNumber')->nullable();
            $table->string('Suffix')->nullable();
            $table->string('ReleaseNumber')->nullable();
            $table->string('Released')->nullable();
            $table->string('Produced')->nullable();
            $table->string('Staged')->nullable();
            $table->string('Shipped')->nullable();
            $table->string('ReleasedValue')->nullable();
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
        Schema::dropIfExists('customer_portal_mains');
    }
}
