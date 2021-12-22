<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAndAddNewFieldInInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('pending', 'on-hold', 'paid') NOT NULL DEFAULT 'pending'");
            DB::statement('ALTER TABLE `invoices` MODIFY COLUMN `amount` FLOAT NOT NULL');
            $table->renameColumn('invoice_id', 'invoice_no');
            $table->double('delivery_cost', 8, 2)->after('amount');
            $table->unsignedInteger('client_id')->after('investigation_id');

            $table->index('client_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('status')->change();
            $table->string('amount')->change();
            $table->renameColumn('invoice_no', 'invoice_id');
            $table->dropColumn('delivery_cost');
            $table->dropColumn('client_id');
        });
    }
}
