<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('investigation_id');
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
            $table->index('investigation_id');
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
        Schema::dropIfExists('invoice_documents');
    }
}
