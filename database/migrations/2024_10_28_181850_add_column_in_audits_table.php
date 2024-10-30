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
        Schema::table('audits', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->foreignId('from_year')->after('description')->nullable()->constrained('fiscal_years')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('to_year')->after('from_year')->nullable()->constrained('fiscal_years')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            //
        });
    }
};
