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
            $table->string('department_letter')->after('department_file')->nullable();
        });

        Schema::table('pending_audit_objections', function (Blueprint $table) {
            $table->string('department_letter')->after('department_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_objections', function (Blueprint $table) {
            $table->dropColumn('department_letter');
        });
        Schema::table('pending_audit_objections', function (Blueprint $table) {
            $table->dropColumn('department_letter');
        });
    }
};
