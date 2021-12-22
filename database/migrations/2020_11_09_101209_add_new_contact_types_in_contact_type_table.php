<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\ContactTypes;

class AddNewContactTypesInContactTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ContactTypes::create(
            [
                'type_name' => 'Contact',
                'type' => 'Contact',  // 1 For Contact
            ]
        );

        ContactTypes::create(
            [
                'type_name' => 'Finance',
                'type' => 'Contact',  // 1 For Contact
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        ContactTypes::where('type_name', 'Contact')->delete();
        ContactTypes::where('type_name', 'Finance')->delete();
    }
}
