<?php

use App\Models\Receipt;
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
        Schema::create('sub_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Receipt::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('receipt_detail');
            $table->unsignedInteger('amount');
            $table->text('file');
            $table->unsignedTinyInteger('status')->default(1);
            $table->text('dy_auditor_remark')->nullable();
            $table->text('dy_mca_remark')->nullable();
            $table->text('mca_remark')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->constrained('users');
            $table->unsignedBigInteger('updated_by')->nullable()->constrained('users');
            $table->unsignedBigInteger('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_receipts');
    }
};
