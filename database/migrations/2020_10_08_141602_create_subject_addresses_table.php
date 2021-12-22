<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subject_id');
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->unsignedInteger('country_id');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('address_type')->nullable();
            $table->timestamps();

            $table->index('subject_id');
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
        Schema::dropIfExists('subject_addresses');
    }
}
