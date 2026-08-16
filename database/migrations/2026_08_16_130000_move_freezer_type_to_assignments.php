<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('freezer_vaccine_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freezer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vaccine_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('started_at');
            $table->date('ended_at')->nullable();
            $table->timestamps();
        });

        foreach (DB::table('freezers')->whereNotNull('vaccine_type_id')->get() as $freezer) {
            DB::table('freezer_vaccine_assignments')->insert([
                'freezer_id' => $freezer->id,
                'vaccine_type_id' => $freezer->vaccine_type_id,
                'recorded_by' => null,
                'started_at' => substr((string) $freezer->created_at, 0, 10),
                'ended_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('freezers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vaccine_type_id');
        });
    }

    public function down(): void
    {
        Schema::table('freezers', function (Blueprint $table) {
            $table->foreignId('vaccine_type_id')->nullable()->constrained()->restrictOnDelete();
        });

        $activeAssignments = DB::table('freezer_vaccine_assignments')->whereNull('ended_at')->get();
        foreach ($activeAssignments as $assignment) {
            DB::table('freezers')->where('id', $assignment->freezer_id)
                ->update(['vaccine_type_id' => $assignment->vaccine_type_id]);
        }

        Schema::dropIfExists('freezer_vaccine_assignments');
    }
};
