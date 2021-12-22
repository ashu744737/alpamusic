<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestigationSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('investigation_id');
            $table->string('family_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('id_number')->nullable();
            $table->string('account_no')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('workplace')->nullable();
            $table->string('website')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('spouse')->nullable();
            $table->string('car_number')->nullable();
            $table->string('req_inv_cost')->nullable();
            $table->text('assistive_details')->nullable();
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
        Schema::dropIfExists('subjects');
    }
}
