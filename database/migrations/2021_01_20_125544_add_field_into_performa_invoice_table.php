<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIntoPerformaInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('performa_invoices', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('paid_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('performa_invoices', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });
    }
}
