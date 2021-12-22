<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPaymentDocFieldInInvestigationDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigation_documents', function (Blueprint $table) {
            $table->integer('is_payment_doc')->default(0)->after('document_type');
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
            $table->dropColumn('is_payment_doc');
        });
    }
}
