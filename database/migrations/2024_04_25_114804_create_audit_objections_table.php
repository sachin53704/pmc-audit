<?php

use App\Models\Audit;
use App\Models\Department;
use App\Models\Zone;
use App\Models\AuditType;
use App\Models\Severity;
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
            $table->foreignIdFor(Audit::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('objection_no');
            $table->date('entry_date');
            $table->foreignIdFor(Department::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Zone::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreignId('from_year')->nullable()->constrained('fiscal_years')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('to_year')->nullable()->constrained('fiscal_years')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(AuditType::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Severity::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(AuditParaCategory::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->double('amount')->nullable();
            $table->string('subject')->nullable();
            $table->string('work_name')->nullable();
            $table->string('contractor_name')->nullable();
            $table->string('document')->nullable();
            $table->string('sub_unit')->nullable();
            $table->longText('description')->nullable();
            $table->longText('department_description')->nullable();
            $table->integer('is_department_answer')->comment("0 => No, 1 => Yes")->default(0);
            $table->unsignedTinyInteger('status')->default(1);
            $table->foreignId('answered_by')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('mca_remark')->nullable();
            $table->text('auditor_remark')->nullable();
            $table->foreignId('approved_by_mca')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('approved_by_auditor')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
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
