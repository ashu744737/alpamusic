<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsInInvestigatorInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigator_investigations', function (Blueprint $table) {
            $table->text('admin_report_subject_summary')->after('completion_summary')->nullable();
            $table->text('admin_report_final_summary')->after('admin_report_subject_summary')->nullable();
            $table->string('inv_cost')->after('admin_report_final_summary')->nullable();
            $table->string('doc_cost')->after('inv_cost')->nullable();
            $table->text('sm_final_summary')->after('doc_cost')->nullable();

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
