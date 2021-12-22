<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsForShareInvestigationDocument extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investigation_documents', function (Blueprint $table) {
            $table->boolean('share_to_client')->after('uploaded_by')->default(0);
            $table->boolean('share_to_investigator')->after('share_to_client')->default(0);
            $table->boolean('share_to_delivery_boy')->after('share_to_investigator')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('investigation_documents', function (Blueprint $table) {
            $table->dropColumn('share_to_client');
            $table->dropColumn('share_to_investigator');
            $table->dropColumn('share_to_delivery_boy');
        });
    }
}
