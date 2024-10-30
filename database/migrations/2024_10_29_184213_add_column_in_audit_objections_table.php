<?php

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
        Schema::table('audit_objections', function (Blueprint $table) {
            $table->string('hmm_draft_number')->nullable()->after('draft_description');
            $table->integer('hmm_draft_dymca_status')->nullable()->comment('blank => Pending, 1 => Approve, 2 => Reject')->after('hmm_draft_number');
            $table->text('hmm_draft_dymca_remark')->nullable()->after('hmm_draft_dymca_status');
            $table->integer('hmm_draft_mca_status')->nullable()->comment('blank => Pending, 1 => Approve, 2 => Reject')->after('hmm_draft_dymca_remark');
            $table->text('hmm_draft_mca_remark')->nullable()->after('hmm_draft_mca_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_objections', function (Blueprint $table) {
            $table->dropColumn('hmm_draft_number');
            $table->dropColumn('hmm_draft_dymca_status');
            $table->dropColumn('hmm_draft_mca_status');
        });
    }
};
