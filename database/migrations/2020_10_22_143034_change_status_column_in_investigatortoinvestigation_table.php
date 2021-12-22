<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStatusColumnInInvestigatortoinvestigationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigator_investigations', function (Blueprint $table) {
            DB::statement("ALTER TABLE investigator_investigations MODIFY COLUMN status ENUM('Assigned', 'Returned To Center', 'Completed', 'Not Completed')");
            DB::statement("ALTER TABLE investigator_investigations DROP COLUMN investigator_name");
            DB::statement("DROP TABLE IF EXISTS `assigned_investigator`");
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
