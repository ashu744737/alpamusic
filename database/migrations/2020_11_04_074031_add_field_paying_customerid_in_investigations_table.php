<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldPayingCustomeridInInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigations', function (Blueprint $table) {
            $table->dropColumn('paying_customer');
            $table->unsignedInteger('paying_customerid')->after('user_inquiry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('investigations', function (Blueprint $table) {
            //
            $table->dropColumn('paying_customerid');
            $table->string('paying_customer')->after('user_inquiry');
        });
    }
}
