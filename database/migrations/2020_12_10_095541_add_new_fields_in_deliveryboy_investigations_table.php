<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsInDeliveryboyInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliveryboy_investigations', function (Blueprint $table) {
            $table->text('completion_subject_summary')->after('completion_time')->nullable();
            $table->text('completion_summary')->after('completion_subject_summary')->nullable();
            $table->text('admin_report_subject_summary')->after('completion_summary')->nullable();
            $table->text('admin_report_final_summary')->after('admin_report_subject_summary')->nullable();
            $table->string('inv_cost')->after('admin_report_final_summary')->nullable();
            $table->string('doc_cost')->after('inv_cost')->nullable();
            $table->text('sm_final_summary')->after('doc_cost')->nullable();
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
        Schema::table('deliveryboy_investigations', function (Blueprint $table) {
            //
        });
    }
}
