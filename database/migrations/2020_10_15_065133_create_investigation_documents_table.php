<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestigationDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investigation_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('investigation_id');
            $table->text('doc_name')->nullable();
            $table->text('file_name')->nullable();
            $table->text('file_path')->nullable();
            $table->string('file_extension')->nullable();
            $table->string('file_size')->nullable();
            $table->unsignedInteger('uploaded_by')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('investigation_documents');
    }
}
