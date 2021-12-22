<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewStatusInStatusColumnInvestigatorInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('status_column_investigator_investigations', function (Blueprint $table) {
            DB::statement("ALTER TABLE investigator_investigations CHANGE COLUMN `status` `status` ENUM('Assigned','Investigation Started','Investigation Declined','Modification Required','Returned To Center','Completed','Not Completed','Declined','Report Writing','Report Submitted')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('status_column_investigator_investigations', function (Blueprint $table) {
            DB::statement("ALTER TABLE investigator_investigations CHANGE COLUMN `status` `status` ENUM('Assigned','Returned To Center','Completed','Not Completed','Declined','Report Writing','Report Submitted')");
        });
    }
}
