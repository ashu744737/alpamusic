<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestigationTransitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investigation_transition', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('investigation_id');
            $table->string('event');
            $table->string('investigation_status')->nullable();
            $table->string('investigation_payment_status')->nullable();
            $table->text('data')->nullable();
            $table->unsignedInteger('perform_by');
            $table->timestamps();

            $table->index('investigation_id');
            $table->index('perform_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investigation_transition');
    }
}
