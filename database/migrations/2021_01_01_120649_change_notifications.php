<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('is_read');
            $table->dropColumn('read_at');
        });

        Schema::create('notification_target', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('notification_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('is_read')->default(0);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index('notification_id');
            $table->index('user_id');
        });

        Schema::rename('user_notifications', 'notifications');
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
