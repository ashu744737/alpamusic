<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformaInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performa_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('investigation_id');
            $table->unsignedInteger('client_id');
            $table->string('invoice_no')->nullable();
            $table->unsignedInteger('paid_by');
            $table->float('amount')->nullable();
            $table->float('discount_amount')->nullable();
            $table->float('parital_amount')->nullable();
            $table->double('delivery_cost', 8, 2)->nullable();
            $table->float('tax')->nullable();
            $table->enum('status', ['pending', 'on-hold', 'paid', 'requested'])->default('pending');
            $table->enum('payment_status', ['full', 'discount', 'parital'])->nullable();
            $table->unsignedInteger('invoice_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('investigation_id');
            $table->index('paid_by');
            $table->index('client_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performa_invoices');
    }
}
