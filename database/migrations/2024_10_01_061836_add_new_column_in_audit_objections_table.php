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
        Schema::table('audit_department_answers', function (Blueprint $table) {
            $table->integer('department_hod_status')->nullable()->comment('0 => reject, 1 => approve, blank => pending')->after('remark');
            $table->text('department_hod_remark')->nullable()->after('department_hod_status');

            $table->integer('department_mca_status')->nullable()->comment('0 => reject, 1 => approve, blank => pending')->after('department_hod_remark');
            $table->text('department_mca_remark')->nullable()->after('department_mca_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_objections', function (Blueprint $table) {
            //
        });
    }
};
