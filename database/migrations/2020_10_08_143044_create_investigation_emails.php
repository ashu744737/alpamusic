<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestigationEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investigation_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('investigation_id');
            $table->string('value')->nullable();
            $table->string('email_type')->nullable();
            $table->timestamps();

            $table->index('investigation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investigation_emails');
    }
}
