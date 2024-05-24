<?php

use App\Models\PaymentReceipt;
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
        Schema::create('sub_payment_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PaymentReceipt::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('receipt_detail');
            $table->unsignedInteger('amount');
            $table->text('file');
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedTinyInteger('dy_auditor_status')->default(0);
            $table->foreignId('action_by_dy_auditor')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('dy_auditor_remark')->nullable();
            $table->unsignedTinyInteger('dy_mca_status')->default(0);
            $table->foreignId('action_by_dy_mca')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('dy_mca_remark')->nullable();
            $table->unsignedTinyInteger('mca_status')->default(0);
            $table->text('mca_remark')->nullable();
            $table->foreignId('action_by_mca')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('sub_payment_receipts');
    }
};
