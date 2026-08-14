<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationAndAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_is_available(): void
    {
        $this->get(route('register'))->assertOk();
    }

    public function test_new_user_can_register_and_always_receives_user_role(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Nowy użytkownik',
            'email' => 'nowy@example.com',
            'password' => 'bezpieczne123',
            'password_confirmation' => 'bezpieczne123',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'nowy@example.com',
            'role' => UserRole::User->value,
        ]);
    }

    public function test_regular_user_cannot_access_admin_area_or_reports(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);

        $this->actingAs($user)->get(route('admin.index'))->assertForbidden();
        $this->actingAs($user)->get(route('reports.index'))->assertForbidden();
    }

    public function test_manager_can_access_reports_but_not_admin_area(): void
    {
        $manager = User::factory()->create(['role' => UserRole::Manager]);

        $this->actingAs($manager)->get(route('reports.index'))->assertOk();
        $this->actingAs($manager)->get(route('admin.index'))->assertForbidden();
    }

    public function test_admin_can_access_every_protected_area(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)->get(route('reports.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.index'))->assertOk();
    }
}
