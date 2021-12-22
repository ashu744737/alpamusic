<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyInvestigatorInvestigationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE investigator_investigations CHANGE COLUMN status status ENUM('Assigned','Return To Center','Completed','Not Completed', 'Declined', 'Report Writing', 'Report Submitted') DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE investigator_investigations CHANGE COLUMN status status ENUM('Assigned','Returned To Center','Completed','Not Completed') DEFAULT NULL");
    }
}
