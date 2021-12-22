<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsInInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigations', function (Blueprint $table) {
            $table->text('decline_reason')->after('deliver_by_manager')->nullable();
            $table->date('decline_date')->after('decline_reason')->nullable();
            $table->integer('decline_by')->after('decline_date')->nullable();
            $table->date('approve_date')->after('decline_by')->nullable();
            $table->integer('approved_by')->after('approve_date')->nullable();
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
            $table->dropColumn('decline_reason');
            $table->dropColumn('decline_date');
            $table->dropColumn('decline_by');
            $table->dropColumn('approve_date');
            $table->dropColumn('approved_by');
        });
    }
}
