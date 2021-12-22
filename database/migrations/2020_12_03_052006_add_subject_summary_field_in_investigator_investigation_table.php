<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubjectSummaryFieldInInvestigatorInvestigationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigator_investigations', function (Blueprint $table) {
            $table->text('completion_subject_summary')->after('completion_time')->nullable();
            $table->text('completion_summary')->after('completion_subject_summary')->nullable();
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
            $table->dropColumn('completion_subject_summary');
            $table->dropColumn('completion_summary');
        });
    }
}
