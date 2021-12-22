<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestigatorBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investigator_bank_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('investigator_id');
            $table->string('name')->nullable();
            $table->string('number')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('branch_number')->nullable();
            $table->string('account_no')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('investigator_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investigator_bank_details');
    }
}
