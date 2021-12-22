<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewDoctypeInDocumentsTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_types', function (Blueprint $table) {
            $types = [
                'name' => 'Final Case Report', 'hr_name' => 'דוח מקרה סופי', 'price' => '0.00',
            ];
            \App\DocumentTypes::insert($types);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents_types', function (Blueprint $table) {
            //
        });
    }
}
