<x-layouts.app title="Logowanie — {{ config('app.name') }}">
    <main class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-12">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.22),_transparent_34%),radial-gradient(circle_at_bottom_right,_rgba(139,92,246,0.18),_transparent_34%)]"></div>
        <section class="relative w-full max-w-md rounded-3xl border border-white/10 bg-white/[0.07] p-8 shadow-2xl shadow-black/30 backdrop-blur-xl sm:p-10">
            <a href="{{ url('/') }}" class="mb-8 inline-flex items-center gap-3 text-sm font-semibold tracking-wide text-white">
                <span class="grid size-10 place-items-center rounded-xl bg-blue-500 shadow-lg shadow-blue-500/25" aria-hidden="true">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3 4 7v10l8 4 8-4V7l-8-4Z"/><path d="m4 7 8 4 8-4M12 11v10"/></svg>
                </span>
                {{ config('app.name', 'Laravel') }}
            </a>
            <header class="mb-8">
                <p class="mb-2 text-sm font-medium text-blue-400">Witaj ponownie</p>
                <h1 class="text-3xl font-bold tracking-tight text-white">Zaloguj się do konta</h1>
                <p class="mt-3 text-sm leading-6 text-slate-400">Wprowadź swoje dane, aby przejść do panelu.</p>
            </header>
            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-200">Adres e-mail</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="block w-full rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/15" placeholder="ty@example.com">
                    @error('email')<p class="mt-2 text-sm text-rose-400" role="alert">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-200">Hasło</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="block w-full rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/15" placeholder="••••••••">
                    @error('password')<p class="mt-2 text-sm text-rose-400" role="alert">{{ $message }}</p>@enderror
                </div>
                <label class="flex cursor-pointer items-center gap-3 text-sm text-slate-300">
                    <input name="remember" type="checkbox" value="1" class="size-4 rounded border-white/20 bg-slate-900 text-blue-500 focus:ring-blue-500/30">Zapamiętaj mnie
                </label>
                <button type="submit" class="w-full rounded-xl bg-blue-500 px-4 py-3 font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-500/30">Zaloguj się</button>
            </form>
            <p class="mt-7 text-center text-sm text-slate-400">Nie masz konta? <a href="{{ route("register") }}" class="font-semibold text-blue-400 hover:text-blue-300">Zarejestruj się</a></p>
            <p class="mt-8 text-center text-xs leading-5 text-slate-500">Dostęp jest przeznaczony wyłącznie dla uprawnionych użytkowników.</p>
        </section>
    </main>
</x-layouts.app>
