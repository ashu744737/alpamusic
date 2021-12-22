<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserPhones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_phones', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_type_id');
            $table->unsignedInteger('user_id');
            $table->string('value')->nullable();
            $table->enum('type', ['phone', 'mobile', 'fax'])->default('phone');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_type_id');
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
        Schema::dropIfExists('user_phones');
    }
}
