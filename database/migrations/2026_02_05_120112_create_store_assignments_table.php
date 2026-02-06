<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_assignments', function (Blueprint $table) {
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('assigned_from');
            $table->dateTime('assigned_to')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->primary(['store_id', 'user_id', 'assigned_from']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_assignments');
    }
};
