<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vaccine_lots', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('lot_number')->unique();
            $table->date('received_at');
            $table->decimal('weight_kg', 10, 3);
            $table->timestamps();
        });

        Schema::create('vaccine_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vaccine_lot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('production_number');
            $table->decimal('quantity_kg', 10, 3);
            $table->date('used_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaccine_usages');
        Schema::dropIfExists('vaccine_lots');
    }
};
