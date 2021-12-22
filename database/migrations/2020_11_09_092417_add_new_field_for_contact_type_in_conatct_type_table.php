<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldForContactTypeInConatctTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_types', function (Blueprint $table) {
            $table->enum('type', ['Default', 'Contact'])->after('type_name')->default('Default');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_types', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
