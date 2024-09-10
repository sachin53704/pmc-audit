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
            $table->text('auditor_remark')->nullable();
            $table->boolean('is_answer_accepted')->nullable();
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
