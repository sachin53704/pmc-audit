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
            $table->integer('dymca_status')->nullable()->comment("1 => Pending,  3 => Rejected,  2 => Accepted")->after('file_path');
            $table->text('dymca_remark')->nullable()->after('dymca_status');
            $table->integer('mca_status')->nullable()->comment("1 => Pending,  3 => Rejected,  2 => Accepted")->after('dymca_remark');
            $table->text('mca_remark')->nullable()->after('mca_status');
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
