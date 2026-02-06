<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE store_conditions MODIFY overall_status VARCHAR(50)");

        Schema::table('store_conditions', function (Blueprint $table) {
            $table->foreignId('condition_type_id')->nullable()->after('visit_id')->constrained('store_condition_types')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('store_conditions', function (Blueprint $table) {
            $table->dropForeign(['condition_type_id']);
            $table->dropColumn('condition_type_id');
        });

        DB::statement("ALTER TABLE store_conditions MODIFY overall_status ENUM('ACTIVE','RISK','POTENTIAL','DROPPED')");
    }
};
