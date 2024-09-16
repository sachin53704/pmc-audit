<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Audit;
use App\Models\AuditObjection;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_department_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Audit::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(AuditObjection::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('file')->nullable();
            $table->text('remark')->nullable();
            $table->boolean('auditor_status')->comment('0 => reject, 1 => approve, blank => pending')->nullable();
            $table->text('auditor_remark')->nullable();

            $table->boolean('dymca_status')->comment('0 => reject, 1 => approve, blank => pending')->nullable();
            $table->text('dymca_remark')->nullable();
            $table->boolean('mca_status')->comment('0 => reject, 1 => approve, blank => pending')->nullable();
            $table->text('mca_remark')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_department_answers');
    }
};
