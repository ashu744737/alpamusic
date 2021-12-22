<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailFieldsIntoUserContactsField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_contacts', function (Blueprint $table) {
            $table->string('primary_email')->nullable()->default(NULL)->after('email');
            $table->string('secondary_email')->nullable()->default(NULL)->after('primary_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_contacts', function (Blueprint $table) {
            $table->dropColumn('referred_by');
            $table->dropColumn('secondary_email');
        });
    }
}
