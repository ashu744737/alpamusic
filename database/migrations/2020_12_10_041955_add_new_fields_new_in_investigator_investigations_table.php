<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsNewInInvestigatorInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigator_investigations', function (Blueprint $table) {
            $table->string('case_reports_accepted')->after('sm_final_summary')->nullable();
            $table->text('docs_accepted')->after('case_reports_accepted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('investigator_investigations', function (Blueprint $table) {
            //
        });
    }
}
