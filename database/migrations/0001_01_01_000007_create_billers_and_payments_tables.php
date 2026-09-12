<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billers', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ne');
            $table->enum('category', ['electricity', 'water', 'internet', 'ward_tax', 'waste'])->default('electricity');
            $table->string('code')->unique();
            $table->string('logo_url')->nullable();
            $table->json('api_config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('citizen_bill_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_id')->constrained('citizens')->cascadeOnDelete();
            $table->foreignId('biller_id')->constrained('billers')->cascadeOnDelete();
            $table->string('consumer_id');
            $table->string('account_holder_name');
            $table->string('nickname')->nullable();
            $table->timestamps();

            $table->unique(['citizen_id', 'biller_id', 'consumer_id']);
        });

        Schema::create('bill_payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('citizen_id')->constrained('citizens')->cascadeOnDelete();
            $table->foreignId('biller_id')->constrained('billers')->cascadeOnDelete();
            $table->string('consumer_id');
            $table->decimal('amount', 10, 2);
            $table->decimal('service_charge', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_gateway')->default('khalti');
            $table->string('gateway_ref_id')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['citizen_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_payments');
        Schema::dropIfExists('citizen_bill_accounts');
        Schema::dropIfExists('billers');
    }
};
