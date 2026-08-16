<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\VaccineLot;
use App\Models\VaccineType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VaccineManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_receive_vaccine_and_record_usage(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        VaccineType::create(['name' => 'Szczepionka A']);

        $this->actingAs($user)->post(route('vaccines.store'), [
            'type' => 'Szczepionka A',
            'lot_number' => 'LOT-001',
            'received_at' => '2026-08-14',
            'weight_kg' => 100,
        ])->assertRedirect();

        $lot = VaccineLot::firstOrFail();

        $this->actingAs($user)->post(route('vaccines.usages.store', $lot), [
            'production_number' => 'PROD-100',
            'quantity_kg' => 25,
            'used_at' => '2026-08-14',
        ])->assertRedirect();

        $this->assertDatabaseHas('vaccine_usages', [
            'vaccine_lot_id' => $lot->id,
            'production_number' => 'PROD-100',
            'quantity_kg' => 25,
        ]);
    }

    public function test_authenticated_user_can_add_new_vaccine_type(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);

        $this->actingAs($user)->post(route('vaccines.types.store'), [
            'name' => 'Nowy typ',
        ])->assertRedirect();

        $this->assertDatabaseHas('vaccine_types', ['name' => 'Nowy typ']);
    }

    public function test_fully_used_lot_becomes_inactive_and_rejects_more_usage(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $lot = VaccineLot::create([
            'type' => 'Szczepionka B',
            'lot_number' => 'LOT-ZERO',
            'received_at' => '2026-08-14',
            'weight_kg' => 50,
        ]);
        $payload = ['production_number' => 'PROD-200', 'quantity_kg' => 50, 'used_at' => '2026-08-14'];

        $this->actingAs($user)->post(route('vaccines.usages.store', $lot), $payload)->assertRedirect();

        $lot->loadSum('usages', 'quantity_kg');
        $this->assertFalse($lot->is_active);
        $this->actingAs($user)->get(route('vaccines.show', $lot))->assertOk()->assertSee('Nieaktywny');
        $this->actingAs($user)->post(route('vaccines.usages.store', $lot), $payload)
            ->assertSessionHasErrors('quantity_kg');
        $this->assertCount(1, $lot->fresh()->usages);
    }

    public function test_active_lots_are_listed_before_inactive_lots(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $active = VaccineLot::create(['type' => 'Aktywna', 'lot_number' => 'A-1', 'received_at' => '2026-08-12', 'weight_kg' => 100]);
        $inactive = VaccineLot::create(['type' => 'Nieaktywna', 'lot_number' => 'N-1', 'received_at' => '2026-08-14', 'weight_kg' => 100]);
        $inactive->usages()->create(['recorded_by' => $user->id, 'production_number' => 'P-1', 'quantity_kg' => 100, 'used_at' => '2026-08-14']);

        $this->actingAs($user)->get(route('vaccines.index'))
            ->assertOk()
            ->assertSeeInOrder([$active->lot_number, $inactive->lot_number]);
    }
}
