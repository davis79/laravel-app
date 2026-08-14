<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_view_user_list(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $user = User::factory()->create(['role' => UserRole::User]);

        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk()->assertSee($user->email);
        $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_admin_can_create_user_with_selected_role(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Jan Manager',
            'email' => 'manager@example.com',
            'role' => UserRole::Manager->value,
            'password' => 'bezpieczne123',
            'password_confirmation' => 'bezpieczne123',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['email' => 'manager@example.com', 'role' => 'manager']);
    }

    public function test_admin_can_edit_another_user_and_change_role(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $user = User::factory()->create(['role' => UserRole::User]);

        $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => 'Zmieniona nazwa',
            'email' => $user->email,
            'role' => UserRole::Manager->value,
            'password' => '',
            'password_confirmation' => '',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Zmieniona nazwa', 'role' => 'manager']);
    }

    public function test_admin_cannot_demote_own_account(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)->put(route('admin.users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => UserRole::User->value,
            'password' => '',
            'password_confirmation' => '',
        ]);

        $this->assertSame(UserRole::Admin, $admin->fresh()->role);
    }
}
