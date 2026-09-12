<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vital_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->nullable()->constrained('applications')->nullOnDelete();
            $table->foreignId('palika_id')->constrained('palikas')->cascadeOnDelete();
            $table->foreignId('ward_id')->constrained('wards')->cascadeOnDelete();
            $table->enum('type', ['birth', 'death', 'marriage', 'divorce', 'migration']);
            $table->string('registration_number')->unique();
            $table->string('event_date_bs')->nullable();
            $table->date('event_date_ad')->nullable();
            $table->string('person_name');
            $table->json('details');
            $table->timestamp('certificate_issued_at')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vital_records');
    }
};
