<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestigationPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investigation_phones', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('investigation_id');
            $table->string('value')->nullable();
            $table->enum('type', ['phone', 'mobile', 'fax'])->default('phone');
            $table->string('phone_type')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('investigation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investigation_phones');
    }
}
