<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_status_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'CLOSED']);
            $table->text('note')->nullable();
            $table->foreignId('changed_by_user_id')->constrained('users');
            $table->dateTime('changed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_status_history');
    }
};
