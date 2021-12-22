<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHrTextFieldsInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_modes', function (Blueprint $table) {
            // $table->renameColumn('mode_name', 'en_mode_name');
            $table->string('hr_mode_name')->nullable()->after('mode_name');
        });
        Schema::table('payment_terms', function (Blueprint $table) {
            // $table->renameColumn('term_name', 'en_term_name');
            $table->string('hr_term_name')->nullable()->after('term_name');
        });
        Schema::table('client_types', function (Blueprint $table) {
            // $table->renameColumn('type_name', 'en_type_name');
            $table->string('hr_type_name')->nullable()->after('type_name');
        });
        Schema::table('contact_types', function (Blueprint $table) {
            // $table->renameColumn('type_name', 'en_type_name');
            $table->string('hr_type_name')->nullable()->after('type_name');
        });
        Schema::table('user_types', function (Blueprint $table) {
            // $table->renameColumn('type_name', 'en_type_name');
            $table->string('hr_type_name')->nullable()->after('type_name');
        });
        Schema::table('delivery_areas', function (Blueprint $table) {
            // $table->renameColumn('area_name', 'en_area_name');
            $table->string('hr_area_name')->nullable()->after('area_name');
        });
        Schema::table('categories', function (Blueprint $table) {
            // $table->renameColumn('name', 'en_name');
            $table->string('hr_name')->nullable()->after('name');
        });
        Schema::table('document_types', function (Blueprint $table) {
            // $table->renameColumn('name', 'en_name');
            $table->string('hr_name')->nullable()->after('name');
        });
        Schema::table('specializations', function (Blueprint $table) {
            // $table->renameColumn('name', 'en_name');
            $table->string('hr_name')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_modes', function (Blueprint $table) {
            // $table->renameColumn('en_mode_name', 'mode_name');
            $table->dropColumn('hr_mode_name');
        });
        Schema::table('payment_modes', function (Blueprint $table) {
            // $table->renameColumn('en_term_name', 'term_name');
            $table->dropColumn('hr_term_name');
        });
        Schema::table('client_types', function (Blueprint $table) {
            // $table->renameColumn('en_type_name', 'type_name');
            $table->dropColumn('hr_type_name');
        });
        Schema::table('contact_types', function (Blueprint $table) {
            // $table->renameColumn('en_type_name', 'type_name');
            $table->dropColumn('hr_type_name');
        });
        Schema::table('user_types', function (Blueprint $table) {
            // $table->renameColumn('en_type_name', 'type_name');
            $table->dropColumn('hr_type_name');
        });
        Schema::table('delivery_areas', function (Blueprint $table) {
            // $table->renameColumn('en_area_name', 'area_name');
            $table->dropColumn('hr_area_name');
        });
        Schema::table('categories', function (Blueprint $table) {
            // $table->renameColumn('en_name', 'name');
            $table->dropColumn('hr_name');
        });
        Schema::table('document_types', function (Blueprint $table) {
            // $table->renameColumn('en_name', 'name');
            $table->dropColumn('hr_name');
        });
        Schema::table('specializations', function (Blueprint $table) {
            // $table->renameColumn('en_name', 'name');
            $table->dropColumn('hr_name');
        });
    }
}
