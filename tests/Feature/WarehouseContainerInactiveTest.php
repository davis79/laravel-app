<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\FruitProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseContainerInactiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_container_becomes_inactive_after_full_usage_and_rejects_more(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $product = FruitProduct::create(['name' => 'Pulpa']);
        $flavor = $product->flavors()->create(['name' => 'Truskawka']);
        $container = $flavor->containers()->create([
            'container_number' => 'K-ZERO',
            'received_at' => '2026-08-12',
            'expires_at' => '2026-10-12',
            'weight_kg' => 100,
        ]);

        $payload = [
            'production_number' => 'PROD-A',
            'quantity_kg' => 100,
            'used_at' => '2026-08-12 12:00:00',
        ];

        $this->actingAs($user)->post(route('warehouse.usages.store', $container), $payload)->assertRedirect();

        $container->loadSum('usages', 'quantity_kg');
        $this->assertFalse($container->is_active);
        $this->actingAs($user)->get(route('warehouse.container', $container))
            ->assertOk()->assertSee('Nieaktywny')->assertDontSee('Zapisz pobranie');

        $this->actingAs($user)->post(route('warehouse.usages.store', $container), $payload)
            ->assertSessionHasErrors('quantity_kg');
        $this->assertCount(1, $container->fresh()->usages);
    }
}
