<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryboyInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveryboy_investigations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('deliveryboy_id');
            $table->unsignedInteger('investigation_id');
            $table->string('charge')->nullable();
            $table->dateTime('returned_at')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['Assigned', 'Returned To Center', 'Delivered', 'Not Delivered']);
            $table->unsignedInteger('assigned_by')->nullable();
            $table->timestamps();

            $table->index('deliveryboy_id');
            $table->index('investigation_id');
            $table->index('assigned_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveryboy_investigations');
    }
}
