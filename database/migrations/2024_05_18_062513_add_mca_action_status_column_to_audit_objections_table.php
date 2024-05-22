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
            $table->unsignedTinyInteger('auditor_action_status')->default(0)->after('status');
            $table->unsignedTinyInteger('mca_action_status')->default(0)->after('auditor_action_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_objections', function (Blueprint $table) {
            $table->dropColumn('mca_action_status');
            $table->dropColumn('auditor_action_status');
        });
    }
};
