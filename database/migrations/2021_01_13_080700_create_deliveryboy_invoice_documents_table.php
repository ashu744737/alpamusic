<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryboyInvoiceDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveryboy_invoice_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('invoice_id');
            $table->string('doc_name');
            $table->text('payment_note');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_extension');
            $table->string('file_size');
            $table->unsignedInteger('uploaded_by');
            $table->timestamps();
            $table->softDeletes();

            $table->index('invoice_id');
            $table->index('uploaded_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveryboy_invoice_documents');
    }
}
