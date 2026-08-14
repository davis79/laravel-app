<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\FruitProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseContainerSortingTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_containers_are_listed_before_inactive_containers(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $product = FruitProduct::create(['name' => 'Produkt']);
        $flavor = $product->flavors()->create(['name' => 'Smak']);

        $active = $flavor->containers()->create([
            'container_number' => 'ACTIVE-001',
            'received_at' => '2026-08-12',
            'expires_at' => '2026-10-12',
            'weight_kg' => 100,
        ]);
        $inactive = $flavor->containers()->create([
            'container_number' => 'INACTIVE-001',
            'received_at' => '2026-08-14',
            'expires_at' => '2026-10-14',
            'weight_kg' => 100,
        ]);
        $inactive->usages()->create([
            'production_name' => 'Produkcja testowa',
            'quantity_kg' => 100,
            'used_at' => '2026-08-14',
            'recorded_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('warehouse.containers', [$product, $flavor]))
            ->assertOk()
            ->assertSeeInOrder([$active->container_number, $inactive->container_number]);
    }
}
