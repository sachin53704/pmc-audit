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
        Schema::table('user_assigned_audits', function (Blueprint $table) {
            $table->date('assign_auditor_date')->after('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_assigned_audits', function (Blueprint $table) {
            $table->dropColumn('assign_auditor_date');
        });
    }
};
