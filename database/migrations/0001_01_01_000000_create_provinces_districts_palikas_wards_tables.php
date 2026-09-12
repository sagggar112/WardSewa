<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ne');
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('provinces')->cascadeOnDelete();
            $table->string('name_en');
            $table->string('name_ne');
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::create('palikas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained('districts')->cascadeOnDelete();
            $table->string('name_en');
            $table->string('name_ne');
            $table->enum('type', ['metropolitan', 'sub_metropolitan', 'municipality', 'rural_municipality'])->default('municipality');
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('palika_id')->constrained('palikas')->cascadeOnDelete();
            $table->integer('ward_number');
            $table->string('office_address')->nullable();
            $table->string('office_phone')->nullable();
            $table->string('office_email')->nullable();
            $table->timestamps();

            $table->unique(['palika_id', 'ward_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wards');
        Schema::dropIfExists('palikas');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('provinces');
    }
};
