<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldOnUserAddressesContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('state')->after('country_id')->nullable();
        });

        Schema::table('user_contacts', function (Blueprint $table) {
            $table->string('fax')->after('mobile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn('state');
        });

        Schema::table('user_contacts', function (Blueprint $table) {
            $table->dropColumn('fax');
        });
    }
}
