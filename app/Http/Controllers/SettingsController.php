<?php

namespace App\Http\Controllers;

use App\Models\UserMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function requiredPassword(): View
    {
        return view('settings.password-required');
    }

    public function index(Request $request): View
    {
        $messages = $request->user()->receivedMessages()->with('sender')->latest()->paginate(15);
        $unreadCount = $request->user()->receivedMessages()->whereNull('read_at')->count();

        return view('settings.index', compact('messages', 'unreadCount'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $wasRequired = $request->user()->passwordRequiresChange();

        $validated = $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
            'password_changed_at' => now(),
            'must_change_password' => false,
        ]);

        if ($wasRequired) {
            return redirect()->route('dashboard')->with('status', 'Hasło zostało zmienione.');
        }

        return back()->with('status', 'Hasło zostało zmienione.');
    }

    public function showMessage(Request $request, UserMessage $message): View
    {
        abort_unless($message->recipient_id === $request->user()->id, 404);

        if ($message->read_at === null) {
            $message->update(['read_at' => now()]);
        }

        return view('settings.message', ['message' => $message->load('sender')]);
    }
}
