<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileIdIntoSubjectsConnectionHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_files_connection', function (Blueprint $table) {
            $table->renameColumn('subject_id', 'file_id');
        });
        Schema::table('subjects', function (Blueprint $table) {
            $table->unsignedInteger('old_file_id')->nullable()->default(NULL)->after('investigation_id')->comment = 'To migrate old systems data with relationship on history_files_connection table';
            $table->unsignedInteger('investigation_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_files_connection', function (Blueprint $table) {
            $table->renameColumn('file_id', 'subject_id');
        });
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('old_file_id');
        });
    }
}
