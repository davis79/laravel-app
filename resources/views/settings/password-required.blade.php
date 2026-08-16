<x-layouts.app title="Zmiana hasła — Production Manager">
    <main class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-12">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.22),_transparent_34%),radial-gradient(circle_at_bottom_right,_rgba(139,92,246,0.18),_transparent_34%)]"></div>
        <section class="relative w-full max-w-md rounded-3xl border border-white/10 bg-white/[0.07] p-8 shadow-2xl shadow-black/30 backdrop-blur-xl sm:p-10">
            <div class="mb-8 flex items-center gap-3">
                <img src="{{ asset('images/danone-logo.png') }}" alt="Danone" class="size-12 shrink-0 object-contain">
                <div><p class="font-semibold text-white">Production Manager</p><p class="text-xs text-slate-500">Bezpieczeństwo konta</p></div>
            </div>

            <header class="mb-7">
                <p class="mb-2 text-sm font-medium text-blue-400">Wymagana zmiana hasła</p>
                <h1 class="text-2xl font-bold tracking-tight text-white">Ustaw nowe hasło</h1>
                <p class="mt-3 text-sm leading-6 text-slate-400">
                    @if(auth()->user()->must_change_password)
                        Logujesz się po raz pierwszy lub administrator ustawił hasło tymczasowe.
                    @else
                        Twoje hasło ma ponad 90 dni i utraciło ważność.
                    @endif
                    Aby kontynuować, ustaw nowe hasło.
                </p>
            </header>

            <form method="POST" action="{{ route('settings.password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <label class="block"><span class="mb-2 block text-sm text-slate-300">Obecne hasło</span><input type="password" name="current_password" autocomplete="current-password" required autofocus class="w-full rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none focus:border-blue-500"></label>
                @error('current_password', 'passwordUpdate')<p class="text-sm text-rose-400">{{ $message }}</p>@enderror
                <label class="block"><span class="mb-2 block text-sm text-slate-300">Nowe hasło</span><input type="password" name="password" autocomplete="new-password" required class="w-full rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none focus:border-blue-500"></label>
                @error('password', 'passwordUpdate')<p class="text-sm text-rose-400">{{ $message }}</p>@enderror
                <label class="block"><span class="mb-2 block text-sm text-slate-300">Powtórz nowe hasło</span><input type="password" name="password_confirmation" autocomplete="new-password" required class="w-full rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none focus:border-blue-500"></label>
                <button class="w-full rounded-xl bg-blue-500 px-4 py-3 font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-400">Zapisz nowe hasło</button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-5 text-center">
                @csrf
                <button class="text-sm text-slate-500 hover:text-white">Wyloguj się</button>
            </form>
        </section>
    </main>
</x-layouts.app>
