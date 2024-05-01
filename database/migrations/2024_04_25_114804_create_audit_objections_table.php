<?php

use App\Models\Audit;
use App\Models\User;
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
            $table->text('objection');
            $table->text('answer')->nullable();
            $table->text('remark')->nullable();
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
