<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fruit_products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('fruit_flavors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fruit_product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('color')->default('indigo');
            $table->timestamps();
            $table->unique(['fruit_product_id', 'name']);
        });

        Schema::create('fruit_containers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fruit_flavor_id')->constrained()->cascadeOnDelete();
            $table->string('container_number')->unique();
            $table->date('received_at');
            $table->date('expires_at');
            $table->decimal('weight_kg', 10, 3);
            $table->timestamps();
        });

        Schema::create('fruit_container_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fruit_container_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('production_name');
            $table->string('production_number')->nullable();
            $table->decimal('quantity_kg', 10, 3);
            $table->dateTime('used_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fruit_container_usages');
        Schema::dropIfExists('fruit_containers');
        Schema::dropIfExists('fruit_flavors');
        Schema::dropIfExists('fruit_products');
    }
};
