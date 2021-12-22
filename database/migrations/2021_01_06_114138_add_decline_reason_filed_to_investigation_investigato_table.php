<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeclineReasonFiledToInvestigationInvestigatoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigator_investigations', function (Blueprint $table) {
            $table->text('decline_reason')->after('returned_at')->nullable();
            $table->date('decline_date')->after('decline_reason')->nullable();
            $table->unsignedInteger('decline_by')->after('decline_date')->nullable();
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
            $table->dropColumn('decline_reason');
            $table->dropColumn('decline_date');
            $table->dropColumn('decline_by');
        });
    }
}
