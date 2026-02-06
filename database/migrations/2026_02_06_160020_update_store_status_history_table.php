<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE store_status_history MODIFY status VARCHAR(50)");

        Schema::table('store_status_history', function (Blueprint $table) {
            $table->foreignId('store_status_id')->nullable()->after('store_id')->constrained('store_statuses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('store_status_history', function (Blueprint $table) {
            $table->dropForeign(['store_status_id']);
            $table->dropColumn('store_status_id');
        });

        DB::statement("ALTER TABLE store_status_history MODIFY status ENUM('ACTIVE','INACTIVE','CLOSED')");
    }
};
