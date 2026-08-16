<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vaccine_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        foreach (\Illuminate\Support\Facades\DB::table('vaccine_lots')->distinct()->pluck('type') as $type) {
            \Illuminate\Support\Facades\DB::table('vaccine_types')->insertOrIgnore([
                'name' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vaccine_types');
    }
};
