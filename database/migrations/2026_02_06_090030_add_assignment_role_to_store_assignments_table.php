<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_assignments', function (Blueprint $table) {
            $table->enum('assignment_role', ['SALES', 'MARKETING', 'SUPERVISOR', 'OTHER'])->default('MARKETING')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('store_assignments', function (Blueprint $table) {
            $table->dropColumn('assignment_role');
        });
    }
};
