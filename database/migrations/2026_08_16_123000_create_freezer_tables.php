<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('freezers', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('vaccine_type_id')->constrained()->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('freezer_temperature_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freezer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('temperature_c', 5, 2);
            $table->dateTime('checked_at');
            $table->timestamps();
        });

        Schema::create('freezer_cleanings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freezer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('cleaned_at');
            $table->date('valid_until');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freezer_cleanings');
        Schema::dropIfExists('freezer_temperature_checks');
        Schema::dropIfExists('freezers');
    }
};
