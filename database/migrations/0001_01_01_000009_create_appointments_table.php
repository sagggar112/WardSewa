<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_number')->unique();
            $table->foreignId('citizen_id')->constrained('citizens')->cascadeOnDelete();
            $table->foreignId('application_id')->nullable()->constrained('applications')->nullOnDelete();
            $table->foreignId('palika_id')->constrained('palikas')->cascadeOnDelete();
            $table->foreignId('ward_id')->constrained('wards')->cascadeOnDelete();
            $table->foreignId('service_type_id')->nullable()->constrained('service_types')->nullOnDelete();
            $table->date('appointment_date');
            $table->string('time_slot'); // e.g. "10:00 AM - 11:00 AM"
            $table->enum('status', [
                'scheduled',
                'confirmed',
                'rescheduled',
                'completed',
                'cancelled',
                'no_show'
            ])->default('scheduled');
            $table->string('purpose');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['ward_id', 'appointment_date', 'status']);
            $table->index(['citizen_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
