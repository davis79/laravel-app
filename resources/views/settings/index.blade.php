<x-warehouse-shell title="Ustawienia" description="Zarządzaj bezpieczeństwem konta i odbieraj wiadomości." root-label="Ustawienia" root-route="settings.index">
    <div class="grid gap-6 xl:grid-cols-[380px_1fr]">
        <section class="rounded-2xl border border-white/[0.07] bg-white/[0.035] p-6">
            <div class="mb-6">
                <p class="text-xs font-bold uppercase tracking-[.16em] text-blue-400">Bezpieczeństwo</p>
                <h2 class="mt-2 text-xl font-semibold text-white">Zmień hasło</h2>
                <p class="mt-2 text-sm text-slate-500">Dla bezpieczeństwa podaj najpierw obecne hasło.</p>
            </div>
            <form method="POST" action="{{ route('settings.password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <label class="block"><span class="mb-2 block text-sm text-slate-400">Obecne hasło</span><input type="password" name="current_password" autocomplete="current-password" class="w-full rounded-xl border border-white/[0.08] bg-black/20 px-4 py-3 text-white outline-none focus:border-blue-500" required></label>
                @error('current_password', 'passwordUpdate')<p class="text-sm text-rose-400">{{ $message }}</p>@enderror
                <label class="block"><span class="mb-2 block text-sm text-slate-400">Nowe hasło</span><input type="password" name="password" autocomplete="new-password" class="w-full rounded-xl border border-white/[0.08] bg-black/20 px-4 py-3 text-white outline-none focus:border-blue-500" required></label>
                @error('password', 'passwordUpdate')<p class="text-sm text-rose-400">{{ $message }}</p>@enderror
                <label class="block"><span class="mb-2 block text-sm text-slate-400">Powtórz nowe hasło</span><input type="password" name="password_confirmation" autocomplete="new-password" class="w-full rounded-xl border border-white/[0.08] bg-black/20 px-4 py-3 text-white outline-none focus:border-blue-500" required></label>
                <button class="w-full rounded-xl bg-blue-500 px-5 py-3 font-semibold text-white transition hover:bg-blue-400">Zmień hasło</button>
            </form>
        </section>

        <section id="wiadomosci" class="rounded-2xl border border-white/[0.07] bg-white/[0.035] p-6">
            <div class="mb-5 flex items-center justify-between gap-4">
                <div><p class="text-xs font-bold uppercase tracking-[.16em] text-blue-400">Wiadomości</p><h2 class="mt-2 text-xl font-semibold text-white">Odebrane</h2></div>
                <div class="rounded-xl bg-blue-500/10 px-3 py-2 text-sm text-blue-300">{{ $unreadCount }} nieprzeczytanych</div>
            </div>
            <div class="space-y-3">
                @forelse($messages as $message)
                    <a href="{{ route('settings.messages.show', $message) }}" class="flex gap-4 rounded-xl border p-4 transition hover:border-blue-500/40 hover:bg-white/[0.04] {{ $message->read_at ? 'border-white/[0.06] bg-black/10' : 'border-blue-500/25 bg-blue-500/[0.07]' }}">
                        <span class="mt-2 size-2 shrink-0 rounded-full {{ $message->read_at ? 'bg-slate-600' : 'bg-blue-400' }}"></span>
                        <span class="min-w-0 flex-1"><span class="flex flex-col justify-between gap-1 sm:flex-row"><strong class="truncate text-sm text-white">{{ $message->subject }}</strong><span class="shrink-0 text-xs text-slate-500">{{ $message->created_at->format('d.m.Y H:i') }}</span></span><span class="mt-1 block text-xs text-slate-500">Od: {{ $message->sender?->name ?? 'System' }}</span><span class="mt-2 block truncate text-sm text-slate-400">{{ $message->body }}</span></span>
                    </a>
                @empty
                    <div class="rounded-xl border border-dashed border-white/[0.08] px-6 py-14 text-center text-slate-500">Nie masz jeszcze żadnych wiadomości.</div>
                @endforelse
            </div>
            <div class="mt-5">{{ $messages->links() }}</div>
        </section>
    </div>
</x-warehouse-shell>
