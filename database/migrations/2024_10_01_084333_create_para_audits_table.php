<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Audit;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('para_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Audit::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->longText('draft_description');
            $table->longText('description')->nullable();
            $table->boolean('is_draft_send')->default(0);
            $table->integer('dymca_status')->nullable()->comment('0 => reject, 1 => approve, blank => pending');
            $table->text('dymca_remark')->nullable();
            $table->integer('mca_status')->nullable()->comment('0 => reject, 1 => approve, blank => pending');
            $table->text('mca_remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('para_audits');
    }
};
