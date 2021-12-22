<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryFieldsInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('is_delivery')->after('price')->nullable()->default(0);
            $table->string('delivery_cost')->after('is_delivery')->nullable();
            $table->unsignedInteger('category_id')->after('delivery_cost')->nullable();

            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_delivery');
            $table->dropColumn('delivery_cost');
            $table->dropColumn('category_id');
        });
    }
}
