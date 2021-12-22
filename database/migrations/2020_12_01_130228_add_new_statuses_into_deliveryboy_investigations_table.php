<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewStatusesIntoDeliveryboyInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE deliveryboy_investigations CHANGE COLUMN status status ENUM('Accepted', 'Rejected', 'Assigned', 'Return To Center', 'Done And Delivered', 'Done And Not Delivered', 'Report Writing', 'Report Submitted') DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE deliveryboy_investigations CHANGE COLUMN status status ENUM('Assigned','Return To Center','Done And Delivered','Done And Not Delivered', 'Report Writing', 'Report Submitted') DEFAULT NULL");
    }
}
