<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('investigation_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('subject');
            $table->enum('type', ['Thank You', 'Complaint']);
            $table->text('message');
            $table->string('file')->nullable();
            $table->enum('status', ['Open', 'Close']);
            $table->timestamps();
            $table->softDeletes();

            $table->index('investigation_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
