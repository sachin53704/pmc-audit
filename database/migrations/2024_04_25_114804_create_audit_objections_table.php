<?php

use App\Models\Audit;
use App\Models\Department;
use App\Models\Zone;
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
            $table->unsignedTinyInteger('status')->default(1);
            $table->integer('dymca_status')->nullable()->comment('1 => Approve, 2 => forward to auditor');
            $table->text('dymca_remark')->nullable();
            $table->integer('mca_status')->nullable()->comment('1 => Approve, 2 => forward to auditor, 3 => forward to department');
            $table->text('mca_remark')->nullable();
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
