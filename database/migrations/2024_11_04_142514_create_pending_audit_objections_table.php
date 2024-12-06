<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AuditObjection;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pending_audit_objections', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AuditObjection::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('parent_id')->nullable();
            $table->integer('sub_unit')->nullable();
            $table->integer('completed_sub_unit')->nullable();
            $table->string('submit_compliance')->nullable();
            $table->integer('pending_sub_unit')->nullable();
            $table->longText('pending_description')->nullable();
            $table->text('ask_pending_auditor_remark')->nullable();
            $table->integer('ask_pending_auditor_status')->nullable();
            $table->string('hmm_draft_letter')->nullable();
            $table->integer('status')->nullable();
            $table->longText('department_remark')->nullable();
            $table->longText('department_draft_remark')->nullable();
            $table->string('department_file')->nullable();
            $table->string('department_letter')->nullable();
            $table->integer('department_hod_final_status')->nullable();
            $table->text('department_hod_final_remark')->nullable();
            $table->integer('department_mca_second_status')->nullable();
            $table->text('department_mca_second_remark')->nullable();
            $table->integer('auditor_status')->nullable();
            $table->longText('auditor_description')->nullable();
            $table->longText('auditor_draft_description')->nullable();
            $table->text('auditor_remark')->nullable();
            $table->integer('dymca_final_status')->nullable();
            $table->text('dymca_final_remark')->nullable();
            $table->integer('mca_final_status')->nullable();
            $table->text('mca_final_remark')->nullable();
            $table->boolean('is_objection_completed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_audit_objections');
    }
};
