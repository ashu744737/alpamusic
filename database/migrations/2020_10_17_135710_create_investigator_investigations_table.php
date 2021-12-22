<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestigatorInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investigator_investigations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('investigator_id');
            $table->unsignedInteger('investigation_id');
            $table->string('charge')->nullable();
            $table->dateTime('returned_at')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->nullable();
            $table->unsignedInteger('assigned_by')->nullable();
            $table->timestamps();

            $table->index('investigator_id');
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
        Schema::dropIfExists('investigator_investigations');
    }
}
