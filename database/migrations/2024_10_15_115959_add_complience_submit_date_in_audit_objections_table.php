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
            $table->datetime('compliance_submit_date')->after('is_department_draft_save')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_objections', function (Blueprint $table) {
            $table->dropColumn('compliance_submit_date');
        });
    }
};
