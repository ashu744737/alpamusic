<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsIntoInvestigatorInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigator_invoices', function (Blueprint $table) {
            $table->date('received_date')->after('payment_status')->nullable();
            $table->date('paid_date')->after('received_date')->nullable();
            $table->unsignedInteger('payment_mode_id')->after('paid_date')->nullable();
            $table->string('bank_details')->after('payment_mode_id')->nullable();
            $table->text('admin_notes')->after('bank_details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('investigator_invoices', function (Blueprint $table) {
            $table->dropColumn('received_date');
            $table->dropColumn('paid_date');
            $table->dropColumn('payment_mode_id');
            $table->dropColumn('bank_details');
            $table->dropColumn('admin_notes');
        });
    }
}
