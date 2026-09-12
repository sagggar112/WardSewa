<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('citizen_id')->nullable()->constrained('citizens')->nullOnDelete();
            $table->foreignId('palika_id')->constrained('palikas')->cascadeOnDelete();
            $table->foreignId('ward_id')->constrained('wards')->cascadeOnDelete();
            $table->enum('category', ['sanitation', 'roads', 'electricity', 'water', 'corruption', 'other'])->default('other');
            $table->string('subject');
            $table->text('description');
            $table->string('location')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('anonymous_contact')->nullable();
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->text('response')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['ward_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
