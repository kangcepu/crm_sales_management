<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_visit_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('visit_id')->constrained('store_visits')->cascadeOnDelete();
            $table->integer('consignment_qty')->default(0);
            $table->decimal('consignment_value', 12, 2)->default(0);
            $table->integer('sales_qty')->default(0);
            $table->decimal('sales_value', 12, 2)->default(0);
            $table->enum('payment_status', ['PAID', 'PENDING']);
            $table->text('competitor_activity')->nullable();
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_visit_reports');
    }
};
