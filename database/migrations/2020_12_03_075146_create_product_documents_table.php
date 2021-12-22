<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id');
            $table->text('doc_name')->nullable();
            $table->text('doc_type')->nullable();
            $table->text('file_name')->nullable();
            $table->text('file_path')->nullable();
            $table->unsignedInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->index('product_id');
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
        Schema::dropIfExists('product_documents');
    }
}
