<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('palika_id')->constrained('palikas')->cascadeOnDelete();
            $table->foreignId('ward_id')->nullable()->constrained('wards')->nullOnDelete(); // null = palika-wide
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->enum('category', ['general', 'procurement', 'tax', 'emergency', 'health', 'event'])->default('general');
            $table->string('attachment_path')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['ward_id', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
