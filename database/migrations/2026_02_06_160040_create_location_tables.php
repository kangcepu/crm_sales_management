<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 10)->unique();
            $table->string('name', 120);
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::create('provinces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->string('code', 20)->nullable();
            $table->string('name', 120);
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('province_id')->constrained('provinces')->cascadeOnDelete();
            $table->string('code', 20)->nullable();
            $table->string('name', 120);
            $table->string('type', 30)->nullable();
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->string('code', 20)->nullable();
            $table->string('name', 120);
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::create('villages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('district_id')->constrained('districts')->cascadeOnDelete();
            $table->string('code', 20)->nullable();
            $table->string('name', 120);
            $table->dateTime('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villages');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('countries');
    }
};
