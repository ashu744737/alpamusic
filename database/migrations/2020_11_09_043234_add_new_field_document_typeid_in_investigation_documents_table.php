<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldDocumentTypeidInInvestigationDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigation_documents', function (Blueprint $table) {

            $table->unsignedInteger('document_typeid')->after('file_size');
            $table->index('document_typeid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('investigation_documents', function (Blueprint $table) {
            $table->dropColumn('document_typeid');
        });
    }
}
