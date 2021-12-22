<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFiledDocumentTypeInInvestigationDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigation_documents', function (Blueprint $table) {
            $table->string('document_type')->default('Other')->after('file_size');
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
            $table->dropColumn('document_type');
        });
    }
}
