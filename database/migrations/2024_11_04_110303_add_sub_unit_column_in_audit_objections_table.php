<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('audit_objections', function (Blueprint $table) {
            $table->integer('completed_sub_unit')->nullable()->after('sub_unit');
            $table->integer('pending_sub_unit')->nullable()->after('completed_sub_unit');
            $table->longText('auditor_description')->nullable()->after('pending_sub_unit');
            $table->longText('auditor_draft_description')->nullable()->after('auditor_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_objections', function (Blueprint $table) {
            $table->dropColumn('completed_sub_unit');
            $table->dropColumn('pending_sub_unit');
            $table->dropColumn('auditor_description');
            $table->dropColumn('auditor_draft_description');
        });
    }
};
