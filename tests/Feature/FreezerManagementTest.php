<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Freezer;
use App\Models\User;
use App\Models\VaccineType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FreezerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_adds_freezer_without_type_and_can_assign_types_over_time(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $firstType = VaccineType::create(['name' => 'Typ A']);
        $secondType = VaccineType::create(['name' => 'Typ B']);

        $this->actingAs($user)->post(route('freezers.store'), ['number' => 'Z-01'])->assertRedirect();
        $freezer = Freezer::firstOrFail();
        $this->assertNull($freezer->currentAssignment);

        $this->actingAs($user)->post(route('freezers.assignments.store', $freezer), [
            'vaccine_type_id' => $firstType->id,
            'started_at' => now()->subDays(37)->toDateString(),
        ])->assertRedirect();
        $this->actingAs($user)->post(route('freezers.assignments.store', $freezer), [
            'vaccine_type_id' => $secondType->id,
            'started_at' => now()->subDays(7)->toDateString(),
        ])->assertRedirect();

        $assignments = $freezer->fresh()->vaccineAssignments()->orderBy('started_at')->get();
        $this->assertCount(2, $assignments);
        $this->assertSame(now()->subDays(7)->toDateString(), $assignments[0]->ended_at->toDateString());
        $this->assertSame($user->id, $assignments[1]->recorded_by);
        $this->actingAs($user)->get(route('freezers.show', $freezer))
            ->assertOk()->assertSeeInOrder(['Typ A', 'Typ B']);
    }

    public function test_user_can_record_temperature_with_author(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $freezer = Freezer::create(['number' => 'Z-02']);

        $this->actingAs($user)->post(route('freezers.temperature.store', $freezer), [
            'temperature_c' => -20.5,
            'checked_at' => now()->subMinute()->format('Y-m-d H:i:s'),
        ])->assertRedirect();

        $this->assertDatabaseHas('freezer_temperature_checks', [
            'freezer_id' => $freezer->id,
            'recorded_by' => $user->id,
            'temperature_c' => -20.5,
        ]);
        $this->actingAs($user)->get(route('freezers.show', $freezer))
            ->assertOk()->assertSee($user->name)->assertSee('Wykonana');
    }

    public function test_cleaning_is_valid_for_thirty_days_and_records_author(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $freezer = Freezer::create(['number' => 'Z-03']);
        $cleanedAt = now()->subMinute();

        $this->actingAs($user)->post(route('freezers.cleaning.store', $freezer), [
            'cleaned_at' => $cleanedAt->format('Y-m-d H:i:s'),
        ])->assertRedirect();

        $this->assertDatabaseHas('freezer_cleanings', [
            'freezer_id' => $freezer->id,
            'recorded_by' => $user->id,
            'valid_until' => $cleanedAt->copy()->addDays(30)->toDateString(),
        ]);
    }

    public function test_freezer_can_be_marked_empty(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $type = VaccineType::create(['name' => 'Typ C']);
        $freezer = Freezer::create(['number' => 'Z-04']);
        $freezer->vaccineAssignments()->create([
            'vaccine_type_id' => $type->id,
            'recorded_by' => $user->id,
            'started_at' => now()->subDays(5),
        ]);

        $this->actingAs($user)->post(route('freezers.assignments.end', $freezer), [
            'ended_at' => now()->toDateString(),
        ])->assertRedirect();

        $this->assertNull($freezer->fresh()->currentAssignment);
    }

    public function test_index_marks_missing_daily_temperature_and_cleaning_as_due(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        Freezer::create(['number' => 'Z-05']);

        $this->actingAs($user)->get(route('freezers.index'))
            ->assertOk()->assertSee('Sprawdź temperaturę')->assertSee('Wymaga mycia')->assertSee('Obecnie pusta');
    }
}
