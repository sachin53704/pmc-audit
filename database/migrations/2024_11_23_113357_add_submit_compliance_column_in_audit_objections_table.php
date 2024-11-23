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
            $table->string('submit_compliance')->after('completed_sub_unit')->nullable();
        });

        Schema::table('pending_audit_objections', function (Blueprint $table) {
            $table->string('submit_compliance')->after('completed_sub_unit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_objections', function (Blueprint $table) {
            $table->dropColumn('submit_compliance');
        });

        Schema::table('pending_audit_objections', function (Blueprint $table) {
            $table->dropColumn('submit_compliance');
        });
    }
};
