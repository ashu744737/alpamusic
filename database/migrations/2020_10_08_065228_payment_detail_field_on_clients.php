<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaymentDetailFieldOnClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('payment_mode_id')->after('contact_type_id')->nullable();
            $table->string('payment_term_id')->after('payment_mode_id')->nullable();
            $table->double('credit_limit', 8, 2)->after('payment_term_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('payment_mode_id');
            $table->dropColumn('payment_term_id');
            $table->dropColumn('credit_limit');
        });
    }
}
