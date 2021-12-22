<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investigations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('work_order_number')->nullable();
            $table->string('user_inquiry')->nullable();
            $table->string('paying_customer')->nullable();
            $table->string('ex_file_claim_no')->nullable();
            $table->string('claim_number')->nullable();
            $table->string('type_of_inquiry')->nullable();
            $table->tinyInteger('make_paste')->nullable()->default(0);
            $table->tinyInteger('deliver_by_manager')->nullable()->default(0);
            $table->string('status')->nullable();
            $table->unsignedInteger('created_by');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
