<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFiledsInInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->double('discount_amount', 8, 2)->after('amount')->nullable()->default(NULL);
            $table->double('parital_amount', 8, 2)->after('discount_amount')->nullable()->default(NULL);
            $table->enum('payment_status', ['full', 'discount', 'parital'])->nullable()->default(NULL)->after('status');
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
            $table->dropColumn('discount_amount');
            $table->dropColumn('parital_amount');
            $table->dropColumn('payment_status');
        });
    }
}
