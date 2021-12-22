<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCaseIdFieldIntoInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigations', function (Blueprint $table) {
            $table->unsignedInteger('case_id')->after('user_id')->nullable();
            $table->index('case_id');
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
            $table->dropIndex('investigations_case_id_index');
            $table->dropColumn('case_id');
        });
    }
}
