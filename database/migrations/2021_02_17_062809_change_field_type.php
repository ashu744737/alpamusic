<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->longText('family_name')->change();
            $table->longText('first_name')->change();
            $table->longText('id_number')->change();
            $table->longText('workplace')->change();
            $table->longText('father_name')->change();
            $table->longText('mother_name')->change();
            $table->longText('spouse_name')->change();
            $table->longText('spouse')->change();
            $table->longText('dob')->change();
            $table->longText('main_email')->change();
            $table->longText('main_phone')->change();
            $table->longText('secondary_phone')->change();
            $table->longText('main_mobile')->change();
            $table->longText('fax')->change();
        });
        Schema::table('subject_addresses', function (Blueprint $table) {
            $table->longText('address2')->change();
            $table->longText('city')->change();
            $table->longText('state')->change();
            $table->longText('street')->change();
            $table->longText('number')->change();
            $table->longText('zipcode')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('family_name')->change();
            $table->string('first_name')->change();
            $table->string('id_number')->change();
            $table->string('workplace')->change();
            $table->string('father_name')->change();
            $table->string('mother_name')->change();
            $table->string('spouse_name')->change();
            $table->string('spouse')->change();
            $table->string('dob')->change();
            $table->string('main_email')->change();
            $table->string('main_phone')->change();
            $table->string('secondary_phone')->change();
            $table->string('main_mobile')->change();
            $table->string('fax')->change();
        });
        Schema::table('subject_addresses', function (Blueprint $table) {
            $table->string('address2')->change();
            $table->string('city')->change();
            $table->string('state')->change();
            $table->string('street')->change();
            $table->string('number')->change();
            $table->string('zipcode')->change();
        });
    }
}
