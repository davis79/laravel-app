<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\UserMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_change_own_password(): void
    {
        $user = User::factory()->create(['password' => 'old-password']);
        $this->actingAs($user)->put(route('settings.password.update'), [
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect()->assertSessionHas('status');

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function test_wrong_current_password_is_rejected(): void
    {
        $user = User::factory()->create(['password' => 'old-password']);
        $this->actingAs($user)->put(route('settings.password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertSessionHasErrorsIn('passwordUpdate', 'current_password');

        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    }

    public function test_user_sees_only_their_messages_and_opening_marks_message_read(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $other = User::factory()->create(['role' => UserRole::User]);
        $own = UserMessage::create(['recipient_id' => $user->id, 'subject' => 'Moja wiadomość', 'body' => 'Treść']);
        UserMessage::create(['recipient_id' => $other->id, 'subject' => 'Cudza wiadomość', 'body' => 'Ukryta']);

        $this->actingAs($user)->get(route('settings.index'))->assertOk()->assertSee('Moja wiadomość')->assertDontSee('Cudza wiadomość');
        $this->get(route('settings.messages.show', $own))->assertOk();
        $this->assertNotNull($own->fresh()->read_at);
    }

    public function test_user_cannot_open_another_users_message(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $message = UserMessage::create(['recipient_id' => User::factory()->create()->id, 'subject' => 'Prywatna', 'body' => 'Treść']);

        $this->actingAs($user)->get(route('settings.messages.show', $message))->assertNotFound();
    }
}
