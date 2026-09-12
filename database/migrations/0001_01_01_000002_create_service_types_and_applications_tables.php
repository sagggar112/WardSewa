<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ne');
            $table->string('code')->unique();
            $table->enum('category', [
                'recommendation',
                'vital_registration',
                'complaint',
                'social_security',
                'business_support',
                'utility'
            ])->default('recommendation');
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->integer('turnaround_days')->default(3);
            $table->json('required_documents')->nullable();
            $table->json('form_fields')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->foreignId('citizen_id')->constrained('citizens')->cascadeOnDelete();
            $table->foreignId('service_type_id')->constrained('service_types')->cascadeOnDelete();
            $table->foreignId('palika_id')->constrained('palikas')->cascadeOnDelete();
            $table->foreignId('ward_id')->constrained('wards')->cascadeOnDelete();
            $table->json('form_data');
            $table->enum('status', [
                'draft',
                'submitted',
                'under_review',
                'documents_requested',
                'approved',
                'rejected',
                'cancelled'
            ])->default('submitted');
            $table->text('remarks')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->enum('payment_status', ['unpaid', 'paid', 'waived'])->default('unpaid');
            $table->decimal('payment_amount', 10, 2)->default(0.00);
            $table->string('payment_method')->nullable();
            $table->string('khalti_transaction_id')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->uuid('qr_code_token')->nullable()->unique();
            $table->string('certificate_path')->nullable();
            $table->timestamps();

            $table->index(['ward_id', 'status']);
            $table->index(['citizen_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
        Schema::dropIfExists('service_types');
    }
};
