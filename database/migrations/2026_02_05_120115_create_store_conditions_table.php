<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_conditions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('visit_id')->constrained('store_visits')->cascadeOnDelete();
            $table->enum('exterior_condition', ['GOOD', 'FAIR', 'BAD']);
            $table->enum('interior_condition', ['GOOD', 'FAIR', 'BAD']);
            $table->string('display_quality');
            $table->string('cleanliness');
            $table->string('shelf_availability');
            $table->enum('overall_status', ['ACTIVE', 'RISK', 'POTENTIAL', 'DROPPED']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_conditions');
    }
};
