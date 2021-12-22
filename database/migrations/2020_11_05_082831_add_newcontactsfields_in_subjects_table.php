<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewcontactsfieldsInSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('main_email')->nullable()->after('passport');
            $table->string('alternate_email')->nullable()->after('main_email');
            $table->string('main_phone')->nullable()->after('alternate_email');
            $table->string('secondary_phone')->nullable()->after('main_phone');
            $table->string('main_mobile')->nullable()->after('secondary_phone');
            $table->string('secondary_mobile')->nullable()->after('main_mobile');
            $table->string('fax')->nullable()->after('secondary_mobile');
            $table->integer('address_confirmed')->default(0)->after('fax');
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
            $table->dropColumn('main_email');
            $table->dropColumn('alternate_email');
            $table->dropColumn('main_phone');
            $table->dropColumn('secondary_phone');
            $table->dropColumn('main_mobile');
            $table->dropColumn('secondary_mobile');
            $table->dropColumn('fax');
            $table->dropColumn('address_confirmed');
        });
    }
}
