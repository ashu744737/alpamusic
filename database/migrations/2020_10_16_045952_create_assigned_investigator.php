<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignedInvestigator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigned_investigator', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('investigation_id');
            $table->unsignedInteger('investigator_id');
            $table->enum('status', ['in progress', 'completed', 'return back', 'inactive'])->default('in progress');
            $table->timestamps();
            $table->softDeletes();

            $table->index('investigation_id');
            $table->index('investigator_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assigned_investigator');
    }
}
