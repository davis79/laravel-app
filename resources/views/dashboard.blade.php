<x-layouts.app title="Panel — {{ config('app.name') }}">
<div class="min-h-screen bg-[#060818] text-slate-300">
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full border-r border-white/[0.06] bg-[#0d1024] transition-transform duration-300 lg:translate-x-0">
        <div class="flex h-20 items-center gap-3 border-b border-white/[0.06] px-6">
            <img src="{{ asset('images/danone-logo.png') }}" alt="Danone" class="size-11 shrink-0 object-contain">
            <div><p class="text-lg font-bold tracking-wide text-white">Danone</p><p class="text-[10px] uppercase tracking-[.18em] text-slate-500">Panel zarządzania</p></div>
        </div>
        <nav class="h-[calc(100vh-5rem)] overflow-y-auto px-4 py-6">
            <p class="px-3 text-[10px] font-bold uppercase tracking-[.18em] text-slate-600">Menu główne</p>
            <a href="{{ route('dashboard') }}" class="mt-3 flex items-center gap-3 rounded-xl bg-indigo-500/15 px-3 py-3 text-sm font-semibold text-indigo-300 ring-1 ring-inset ring-indigo-500/20">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/></svg>
                Pulpit
            </a>
            <a href="{{ route('warehouse.products') }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16v13H4z M7 7V4h10v3 M8 11h8 M8 15h5"/></svg>Magazyn owocowy
            </a>
            <a href="{{ route('vaccines.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 3h6v3l2 2v12H7V8l2-2V3ZM9 12h6M12 9v6"/></svg>Szczepionki
            </a>
            <a href="{{ route('freezers.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2v20M4.5 6.5l15 11M19.5 6.5l-15 11M4 12h16"/></svg>Zamrażarki
            </a>
            @if(auth()->user()->role->value === 'admin')
            <a href="{{ route('admin.users.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM19 8v6M22 11h-6"/></svg>
                Użytkownicy
            </a>
            @endif
            @foreach ([
                ['Karty produkcyjne','M5 3v4M19 3v4M3 9h18M5 5h14a2 2 0 0 1 2 2v13H3V7a2 2 0 0 1 2-2Z'],
                ['Planowanie','M6 3h12v18l-3-2-3 2-3-2-3 2V3Z'],
            ] as [$label,$path])
            <a href="#" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="{{ $path }}"/></svg>{{ $label }}
            </a>
            @endforeach
            <p class="mt-8 px-3 text-[10px] font-bold uppercase tracking-[.18em] text-slate-600">Narzędzia</p>
            @if(in_array(auth()->user()->role->value, ['admin','manager'], true))<a href="{{ route("reports.index") }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white"><span class="size-2 rounded-full border border-slate-600"></span>Raporty</a>@endif
            @if(auth()->user()->role->value === 'admin')<a href="{{ route("admin.index") }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white"><span class="size-2 rounded-full border border-slate-600"></span>Administracja</a>@endif
            <a href="#" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white"><span class="size-2 rounded-full border border-slate-600"></span>Integracje</a>
            <a href="{{ route('settings.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white"><span class="size-2 rounded-full border border-slate-600"></span>Ustawienia</a>
        </nav>
    </aside>
    <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-black/60 backdrop-blur-sm lg:hidden"></div>

    <div class="lg:pl-64">
        <header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-white/[0.06] bg-[#060818]/85 px-4 backdrop-blur-xl sm:px-8">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="grid size-10 place-items-center rounded-xl bg-white/[0.05] text-slate-300 lg:hidden" aria-label="Otwórz menu">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
                <div class="hidden items-center gap-3 rounded-xl bg-white/[0.04] px-4 py-2.5 sm:flex">
                    <svg class="size-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                    <input class="w-44 bg-transparent text-sm outline-none placeholder:text-slate-600" placeholder="Szukaj w panelu...">
                    <kbd class="rounded border border-white/10 px-1.5 py-0.5 text-[10px] text-slate-600">⌘K</kbd>
                </div>
            </div>
            @include('components.user-toolbar')
        </header>

        <main class="p-4 sm:p-8">
            <div class="mx-auto max-w-[1500px]">
                <div class="mb-8">
                    <p class="text-sm font-medium text-blue-400">Production Manager</p>
                    <h1 class="mt-1 text-2xl font-bold text-white sm:text-3xl">Dzie&#324; dobry, {{ auth()->user()->name }} &#128075;</h1>
                    <p class="mt-2 text-sm text-slate-500">Poniżej znajdziesz najważniejsze zmiany wprowadzone w aplikacji.</p>
                </div>

                @php
                    $changes = [
                        ['16.08.2026', 'Ustawienia i wiadomości', 'Każdy użytkownik może zmienić własne hasło oraz odczytywać prywatne wiadomości. Dzwonek wskazuje nowe wiadomości.', 'blue'],
                        ['16.08.2026', 'Zamrażarki', 'Dodano rejestr zamrażarek, historię przechowywanych typów szczepionek, codzienną kontrolę temperatury i 30-dniowy rejestr mycia.', 'cyan'],
                        ['14.08.2026', 'Magazyn szczepionek', 'Dodano typy i partie szczepionek oraz rozliczanie pobrań według numeru produkcji, ilości i daty.', 'emerald'],
                        ['12.08.2026', 'Magazyn owocowy', 'Wdrożono produkty, smaki i kontenery wraz z datami, wagą, zużyciem oraz automatycznym oznaczaniem pustych kontenerów jako nieaktywne.', 'amber'],
                        ['12.08.2026', 'Zarządzanie użytkownikami', 'Administrator może dodawać i edytować użytkowników oraz zmieniać ich role i uprawnienia.', 'violet'],
                        ['12.08.2026', 'Role i bezpieczeństwo', 'Wprowadzono role użytkownika, menadżera i administratora oraz ograniczenia dostępu do wybranych funkcji.', 'rose'],
                        ['12.08.2026', 'Nowy wygląd aplikacji', 'Ujednolicono wygląd panelu, dodano branding Danone oraz tryby jasny, ciemny i systemowy.', 'indigo'],
                        ['12.08.2026', 'Logowanie', 'Strona logowania została ustawiona jako ekran startowy aplikacji.', 'slate'],
                    ];
                @endphp

                <section class="overflow-hidden rounded-2xl border border-white/[0.07] bg-[#0d1024]">
                    <div class="flex flex-col justify-between gap-3 border-b border-white/[0.07] px-5 py-5 sm:flex-row sm:items-center sm:px-7">
                        <div>
                            <h2 class="text-lg font-semibold text-white">Lista zmian w aplikacji</h2>
                            <p class="mt-1 text-sm text-slate-500">Historia najważniejszych wdrożonych funkcji i usprawnień.</p>
                        </div>
                        <span class="w-fit rounded-full bg-blue-500/10 px-3 py-1.5 text-xs font-semibold text-blue-300">{{ count($changes) }} zmian</span>
                    </div>

                    <div class="divide-y divide-white/[0.06]">
                        @foreach($changes as [$date, $title, $description, $color])
                            <article class="group flex gap-4 px-5 py-5 transition hover:bg-white/[0.025] sm:gap-6 sm:px-7">
                                <div class="flex flex-col items-center">
                                    <span class="mt-1 size-3 rounded-full bg-{{ $color }}-400 ring-4 ring-{{ $color }}-400/10"></span>
                                    @unless($loop->last)<span class="mt-2 h-full w-px bg-white/[0.07]"></span>@endunless
                                </div>
                                <div class="min-w-0 flex-1 pb-1">
                                    <div class="flex flex-col justify-between gap-1 sm:flex-row sm:items-center">
                                        <h3 class="font-semibold text-slate-100">{{ $title }}</h3>
                                        <time class="shrink-0 text-xs text-slate-500">{{ $date }}</time>
                                    </div>
                                    <p class="mt-2 max-w-5xl text-sm leading-6 text-slate-400">{{ $description }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
                <footer class="mt-8 flex flex-col justify-between gap-2 border-t border-white/[0.05] py-6 text-xs text-slate-600 sm:flex-row"><p>© {{ date('Y') }} {{ config('app.name', 'Nexus') }}. Wszystkie prawa zastrzeżone.</p><p>Panel administracyjny v1.0</p></footer>
            </div>
        </main>
    </div>
</div>
</x-layouts.app>
