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
        Schema::create('outward_nos', function (Blueprint $table) {
            $table->id();
            $table->string('table_id');
            $table->enum('letter', ['1', '2', '3', '4']);
            $table->string('outward_no');
            $table->string('table');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outward_nos');
    }
};
