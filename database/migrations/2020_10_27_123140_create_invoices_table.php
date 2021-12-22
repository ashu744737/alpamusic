<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('investigation_id');
            $table->string('invoice_id')->nullable();
            $table->unsignedInteger('paid_by');
            $table->string('amount');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();

            $table->index('investigation_id');
            $table->index('paid_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
