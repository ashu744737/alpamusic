<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryboyInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveryboy_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('investigation_id');
            $table->unsignedInteger('deliveryboy_id');
            $table->string('invoice_no');
            $table->float('amount')->nullable();
            $table->float('discount_amount')->nullable();
            $table->float('parital_amount')->nullable();
            $table->text('client_payment_notes')->nullable();;
            $table->enum('status', ['pending', 'on-hold', 'paid', 'requested'])->default('pending');
            $table->enum('payment_status', ['full', 'discount', 'parital'])->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('investigation_id');
            $table->index('deliveryboy_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveryboy_invoices');
    }
}
