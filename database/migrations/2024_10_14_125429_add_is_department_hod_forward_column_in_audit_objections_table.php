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
            $table->boolean('is_department_draft_save')->default(0)->after('is_draft_send');
            $table->boolean('is_department_hod_forward')->default(0)->after('is_objection_send');
            $table->text('department_hod_remark')->nullable()->after('is_department_hod_forward');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_objections', function (Blueprint $table) {
            $table->dropColumn('is_department_draft_save');
            $table->dropColumn('is_department_hod_forward');
            $table->dropColumn('department_hod_remark');
        });
    }
};
