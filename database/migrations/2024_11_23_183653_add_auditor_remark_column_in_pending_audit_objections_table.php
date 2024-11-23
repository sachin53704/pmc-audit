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
        Schema::table('pending_audit_objections', function (Blueprint $table) {
            $table->text('ask_pending_auditor_remark')->nullable()->after('pending_description');
            $table->integer('ask_pending_auditor_status')->nullable()->after('ask_pending_auditor_remark');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pending_audit_objections', function (Blueprint $table) {
            $table->dropColumn('ask_pending_auditor_remark');
            $table->dropColumn('ask_pending_auditor_status');
        });
    }
};
