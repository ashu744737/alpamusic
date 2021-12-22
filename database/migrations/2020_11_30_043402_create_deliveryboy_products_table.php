<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryboyProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveryboy_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('deliveryboy_id');
            $table->unsignedInteger('product_id');
            $table->double('price', 8, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('deliveryboy_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveryboy_products');
    }
}
