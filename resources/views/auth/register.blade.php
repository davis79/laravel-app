<x-layouts.app title="Rejestracja — Production Manager">
<main class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-12">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(99,102,241,0.23),_transparent_35%),radial-gradient(circle_at_bottom_left,_rgba(34,211,238,0.13),_transparent_34%)]"></div>
    <section class="relative w-full max-w-md rounded-3xl border border-white/10 bg-white/[0.07] p-8 shadow-2xl shadow-black/30 backdrop-blur-xl sm:p-10">
        <a href="{{ url('/') }}" class="mb-8 inline-flex items-center gap-3 text-sm font-semibold tracking-wide text-white">
            <img src="{{ asset('images/danone-logo.png') }}" alt="Danone" class="size-12 shrink-0 object-contain">
            <span>Production Manager</span>
        </a>
        <header class="mb-7"><p class="mb-2 text-sm font-medium text-indigo-400">Utwórz konto</p><h1 class="text-3xl font-bold tracking-tight text-white">Dołącz do nas</h1><p class="mt-3 text-sm leading-6 text-slate-400">Wypełnij formularz, aby otrzymać dostęp użytkownika.</p></header>
        <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
            @csrf
            <div><label for="name" class="mb-2 block text-sm font-medium text-slate-200">Imię i nazwisko</label><input id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="block w-full rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/15">@error('name')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror</div>
            <div><label for="email" class="mb-2 block text-sm font-medium text-slate-200">Adres e-mail</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" class="block w-full rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/15">@error('email')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror</div>
            <div><label for="password" class="mb-2 block text-sm font-medium text-slate-200">Hasło</label><input id="password" name="password" type="password" required autocomplete="new-password" class="block w-full rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/15">@error('password')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror</div>
            <div><label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-200">Powtórz hasło</label><input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="block w-full rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/15"></div>
            <button class="w-full rounded-xl bg-indigo-500 px-4 py-3 font-semibold text-white shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/30">Utwórz konto</button>
        </form>
        <p class="mt-7 text-center text-sm text-slate-400">Masz już konto? <a href="{{ route('login') }}" class="font-semibold text-indigo-400 hover:text-indigo-300">Zaloguj się</a></p>
    </section>
</main>
</x-layouts.app>
