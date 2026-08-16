<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordExpirationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_created_user_must_change_password_after_first_login(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Nowy Użytkownik',
            'email' => 'new@example.com',
            'role' => UserRole::User->value,
            'password' => 'temporary123',
            'password_confirmation' => 'temporary123',
        ]);

        $user = User::where('email', 'new@example.com')->firstOrFail();
        $this->assertTrue($user->must_change_password);
        $this->assertNull($user->password_changed_at);

        auth()->logout();
        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'temporary123'])
            ->assertRedirect(route('settings.password.required'));
    }

    public function test_expired_password_blocks_application_until_changed(): void
    {
        $user = User::factory()->create([
            'password' => 'old-password',
            'password_changed_at' => now()->subDays(91),
        ]);

        $this->actingAs($user)->get(route('dashboard'))->assertRedirect(route('settings.password.required'));
        $this->get(route('settings.password.required'))->assertOk();

        $this->put(route('settings.password.update'), [
            'current_password' => 'old-password',
            'password' => 'fresh-password',
            'password_confirmation' => 'fresh-password',
        ])->assertRedirect(route('dashboard'));

        $user->refresh();
        $this->assertFalse($user->must_change_password);
        $this->assertTrue(Hash::check('fresh-password', $user->password));
        $this->assertTrue($user->password_changed_at->greaterThan(now()->subMinute()));
        $this->get(route('dashboard'))->assertOk();
    }

    public function test_password_is_valid_for_exactly_ninety_days(): void
    {
        $user = User::factory()->create(['role' => UserRole::User, 'password_changed_at' => now()->subDays(89)]);
        $this->actingAs($user)->get(route('dashboard'))->assertOk();
    }
}
