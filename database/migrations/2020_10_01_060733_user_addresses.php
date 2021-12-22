<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_type_id');
            $table->unsignedInteger('user_id');
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->unsignedInteger('country_id');
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('zipcode')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_type_id');
            $table->index('user_id');
            $table->index('country_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
}
