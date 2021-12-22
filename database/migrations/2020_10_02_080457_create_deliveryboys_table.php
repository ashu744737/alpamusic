<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryboysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveryboys', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('family')->nullable();
            $table->string('idnumber')->nullable();
            $table->string('website')->nullable();
            $table->date('dob')->nullable();
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveryboys');
    }
}
