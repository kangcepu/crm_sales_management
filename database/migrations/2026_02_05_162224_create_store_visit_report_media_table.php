<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_visit_report_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('report_id')->constrained('store_visit_reports')->cascadeOnDelete();
            $table->enum('media_type', ['PHOTO', 'VIDEO']);
            $table->string('media_url');
            $table->string('caption')->nullable();
            $table->dateTime('taken_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_visit_report_media');
    }
};
