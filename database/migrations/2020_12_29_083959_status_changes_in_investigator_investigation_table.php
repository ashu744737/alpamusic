<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StatusChangesInInvestigatorInvestigationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigator_investigations', function (Blueprint $table) {
            DB::statement("ALTER TABLE investigator_investigations CHANGE COLUMN `status` `status` ENUM('Assigned','Investigation Started','Investigation Declined','Modification Required','Returned To Center','Completed','Not Completed','Declined','Report Writing','Report Submitted','Completed Without Findings','Completed With Findings','Report Accepted','Report Declined','Returned To Investigator','Final Report Submitted','Final Report Accepted','Final Report Declined')");
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
            DB::statement("ALTER TABLE investigator_investigations CHANGE COLUMN `status` `status` ENUM('Assigned','Investigation Started','Investigation Declined','Modification Required','Returned To Center','Completed','Not Completed','Declined','Report Writing','Report Submitted','Completed Without Findings','Completed With Findings','Report Accepted','Report Declined','Final Report Submitted','Final Report Accepted','Final Report Declined')");
        });
    }
}
