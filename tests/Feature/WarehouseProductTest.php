<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_sees_add_product_button_and_can_create_product(): void
    {
        $manager = User::factory()->create(['role' => UserRole::Manager]);

        $this->actingAs($manager)->get(route('warehouse.products'))
            ->assertOk()
            ->assertSee('Dodaj produkt');

        $this->actingAs($manager)->post(route('warehouse.products.store'), [
            'name' => 'Pulpa owocowa',
            'description' => 'Produkt testowy',
        ])->assertRedirect();

        $this->assertDatabaseHas('fruit_products', ['name' => 'Pulpa owocowa']);
    }

    public function test_regular_user_can_view_warehouse_but_cannot_create_product(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);

        $this->actingAs($user)->get(route('warehouse.products'))->assertOk();
        $this->actingAs($user)->post(route('warehouse.products.store'), ['name' => 'Niedozwolony'])->assertForbidden();
    }
}
