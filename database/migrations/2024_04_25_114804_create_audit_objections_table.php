<?php

use App\Models\Audit;
use App\Models\Department;
use App\Models\AuditType;
use App\Models\Severity;
use App\Models\User;
use App\Models\AuditParaCategory;
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
        Schema::create('audit_objections', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Audit::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('objection_no');
            $table->date('entry_date');
            $table->foreignIdFor(Department::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('from_year')->nullable()->constrained('fiscal_years')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('to_year')->nullable()->constrained('fiscal_years')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(AuditType::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Severity::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(AuditParaCategory::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->double('amount')->nullable();
            $table->text('subject')->nullable();
            $table->string('document')->nullable();
            $table->string('sub_unit')->nullable();
            $table->integer('completed_sub_unit')->nullable();
            $table->string('submit_compliance')->nullable();
            $table->integer('pending_sub_unit')->nullable();
            $table->longText('auditor_description')->nullable();
            $table->longText('auditor_draft_description')->nullable();
            $table->longText('description')->nullable();
            $table->longText('draft_description')->nullable();
            $table->string('hmm_draft_number')->nullable();
            $table->string('clerk_send_hmm_draft_letter')->nullable();
            $table->integer('hmm_draft_dymca_status')->nullable()->comment('blank => Pending, 1 => Approve, 2 => Reject');
            $table->text('hmm_draft_dymca_remark')->nullable();
            $table->integer('hmm_draft_mca_status')->nullable()->comment('blank => Pending, 1 => Approve, 2 => Reject');
            $table->text('hmm_draft_mca_remark')->nullable();
            $table->string('hmm_draft_letter')->nullable();
            $table->boolean('is_draft_send')->default(0);
            $table->boolean('is_department_draft_save')->nullable();
            $table->datetime('compliance_submit_date')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->boolean('is_draft_save')->default(0);
            $table->boolean('is_objection_send')->default(0);
            $table->boolean('is_department_hod_forward')->default(0);
            $table->text('department_hod_remark')->nullable();
            $table->integer('dymca_status')->nullable()->comment('1 => Approve, 2 => forward to auditor');
            $table->text('dymca_remark')->nullable();
            $table->integer('mca_status')->nullable()->comment('1 => Approve, 2 => forward to auditor, 3 => forward to department');
            $table->text('mca_remark')->nullable();
            $table->string('department_file')->nullable();
            $table->string('department_letter')->nullable();
            $table->longtext('department_remark')->nullable();
            $table->longtext('department_draft_remark')->nullable();
            $table->integer('department_hod_final_status')->nullable()->comment('0 => reject, 1 => approve, blank => pending');
            $table->text('department_hod_final_remark')->nullable();
            $table->integer('department_mca_second_status')->nullable()->comment('	0 => reject, 1 => approve, blank => pending');
            $table->text('department_mca_second_remark')->nullable();
            $table->integer('auditor_status')->nullable()->comment('	0 => reject, 1 => approve, blank => pending');
            $table->text('auditor_remark')->nullable();
            $table->integer('dymca_final_status')->nullable()->comment('0 => reject, 1 => approve, blank => pending');
            $table->text('dymca_final_remark')->nullable();
            $table->integer('mca_final_status')->nullable()->comment('	0 => reject, 1 => approve, blank => pending');
            $table->text('mca_final_remark')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_objections');
    }
};
