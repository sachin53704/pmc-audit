<?php

use App\Models\Department;
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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Department::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('from_year')->nullable()->constrained('fiscal_years')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('to_year')->nullable()->constrained('fiscal_years')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('audit_no', 50);
            $table->date('date');
            $table->date('audit_start_date')->nullable();
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('dymca_status')->nullable()->comment("1 => Pending,  3 => Rejected,  2 => Accepted");
            $table->text('dymca_remark')->nullable();
            $table->integer('mca_status')->nullable()->comment("1 => Pending,  3 => Rejected,  2 => Accepted");
            $table->text('mca_remark')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->text('reject_reason')->nullable();
            $table->text('dl_description')->comment("Department Letter Description")->nullable();
            $table->string('dl_file_path')->comment("Department Letter File")->nullable();
            $table->date('obj_date')->comment("Objection Date")->nullable();
            $table->text('obj_subject')->comment("Objection Subject")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
