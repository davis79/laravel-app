<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\FruitProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseContainerBatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_multiple_containers_can_be_saved_in_one_request(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $product = FruitProduct::create(['name' => 'Pulpa']);
        $flavor = $product->flavors()->create(['name' => 'Malina']);

        $this->actingAs($user)->post(route('warehouse.containers.store', [$product, $flavor]), [
            'containers' => [
                ['container_number' => 'K-001', 'received_at' => '2026-08-12', 'expires_at' => '2026-09-12', 'weight_kg' => '100.500'],
                ['container_number' => 'K-002', 'received_at' => '2026-08-13', 'expires_at' => '2026-09-13', 'weight_kg' => '90'],
                ['container_number' => 'K-003', 'received_at' => '2026-08-14', 'expires_at' => '2026-09-14', 'weight_kg' => '80.250'],
            ],
        ])->assertRedirect();

        $this->assertDatabaseCount('fruit_containers', 3);
    }

    public function test_batch_is_rejected_when_expiry_precedes_received_date(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $product = FruitProduct::create(['name' => 'Koncentrat']);
        $flavor = $product->flavors()->create(['name' => 'Wiśnia']);

        $this->actingAs($user)->post(route('warehouse.containers.store', [$product, $flavor]), [
            'containers' => [
                ['container_number' => 'K-BAD', 'received_at' => '2026-09-12', 'expires_at' => '2026-08-12', 'weight_kg' => '50'],
            ],
        ])->assertSessionHasErrors('containers.0.expires_at');

        $this->assertDatabaseCount('fruit_containers', 0);
    }
}
