<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRejectReasonForInvestigatorDeliveryboy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliveryboy_investigations', function (Blueprint $table) {
            $table->text('reject_reason')->nullable()->default(NULL)->after('assigned_by');
        });
        Schema::table('investigator_investigations', function (Blueprint $table) {
            $table->text('reject_reason')->nullable()->default(NULL)->after('assigned_by');
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
            $table->dropColumn('reject_reason');
        });
        Schema::table('investigator_investigations', function (Blueprint $table) {
            $table->dropColumn('reject_reason');
        });
    }
}
