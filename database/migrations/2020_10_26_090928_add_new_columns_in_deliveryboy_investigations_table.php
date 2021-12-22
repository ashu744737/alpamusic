<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsInDeliveryboyInvestigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliveryboy_investigations', function (Blueprint $table) {
            $table->unsignedInteger('type_of_inquiry')->nullable()->after('investigation_id');
            $table->date('start_date')->nullable()->after('type_of_inquiry');
            $table->time('start_time')->nullable()->after('start_date');
            $table->date('completion_date')->nullable()->after('start_time');
            $table->string('completion_time')->nullable()->after('completion_date');

            $table->index('type_of_inquiry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deliveryboy_investigations', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('start_time');
            $table->dropColumn('completion_date');
            $table->dropColumn('completion_time');

            $table->dropIndex('deliveryboy_investigations_type_of_inquiry_index');
            $table->dropColumn('type_of_inquiry');
        });
    }
}
