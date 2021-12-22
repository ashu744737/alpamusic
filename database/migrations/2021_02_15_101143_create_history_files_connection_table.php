<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryFilesConnectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_files_connection', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subject_id');
            $table->timestamp('attach_date')->nullable();
            $table->text('source_name')->nullable();
            $table->text('file_name')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('is_delivered')->default(0);
            $table->timestamps();

            $table->index('subject_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_files_connection');
    }
}
