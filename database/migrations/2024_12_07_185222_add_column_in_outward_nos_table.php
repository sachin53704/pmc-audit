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
        Schema::table('outward_nos', function (Blueprint $table) {
            $table->dropColumn('table_id');
            $table->dropColumn('letter');
            $table->dropColumn('table');
            $table->string('department_id')->nullable()->after('id');
            $table->text('subject')->nullable()->after('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outward_nos', function (Blueprint $table) {
            //
        });
    }
};
