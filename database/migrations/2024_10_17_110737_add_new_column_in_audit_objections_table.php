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
            $table->string('department_file')->after('mca_remark')->nullable();
            $table->longtext('department_remark')->after('department_file')->nullable();
            $table->longtext('department_draft_remark')->after('department_remark')->nullable();
            $table->integer('department_hod_final_status')->after('department_draft_remark')->nullable()->comment('	0 => reject, 1 => approve, blank => pending');
            $table->text('department_hod_final_remark')->after('department_hod_final_status')->nullable();
            $table->integer('department_mca_second_status')->after('department_hod_final_remark')->nullable()->comment('	0 => reject, 1 => approve, blank => pending');
            $table->text('department_mca_second_remark')->after('department_mca_second_status')->nullable();
            $table->integer('auditor_status')->after('department_mca_second_remark')->nullable()->comment('	0 => reject, 1 => approve, blank => pending');
            $table->text('auditor_remark')->after('auditor_status')->nullable();
            $table->integer('dymca_final_status')->after('auditor_remark')->nullable()->comment('	0 => reject, 1 => approve, blank => pending');
            $table->text('dymca_final_remark')->after('dymca_final_status')->nullable();
            $table->integer('mca_final_status')->after('dymca_final_remark')->nullable()->comment('	0 => reject, 1 => approve, blank => pending');
            $table->text('mca_final_remark')->after('mca_final_status')->nullable();
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
