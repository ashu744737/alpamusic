<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewValuesInUserStatusField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users` CHANGE `status` `status` ENUM('pending','email verified','approved','active','inavcitve','disabled') NOT NULL DEFAULT 'disabled'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `users` CHANGE `status` `status` ENUM('pending', 'approved', 'disabled') NOT NULL DEFAULT 'disabled'");
    }
}
