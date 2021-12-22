<?php

use App\ClientCustomer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCustomerIdInClientCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ClientCustomer::all()->delete();
        DB::table('client_customers')->delete();

        Schema::table('client_customers', function (Blueprint $table) {
            $table->unsignedInteger('customer_id')->after('client_id');

            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_customers', function (Blueprint $table) {
            $table->dropColumn('customer_id');
        });
    }
}
