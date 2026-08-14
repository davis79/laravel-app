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
            @if(in_array(auth()->user()->role->value, ['admin','manager'], true))
            <a href="{{ route('warehouse.products') }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16v13H4z M7 7V4h10v3 M8 11h8 M8 15h5"/></svg>Magazyn owocowy
            @include("components.warehouse.tree")
            </a>
            @endif
            @if(auth()->user()->role->value === 'admin')
            <a href="{{ route('admin.users.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM19 8v6M22 11h-6"/></svg>
                Użytkownicy
            </a>
            @endif
            @foreach ([
                ['Kalendarz','M5 3v4M19 3v4M3 9h18M5 5h14a2 2 0 0 1 2 2v13H3V7a2 2 0 0 1 2-2Z'],
                ['Faktury','M6 3h12v18l-3-2-3 2-3-2-3 2V3Z'],
            ] as [$label,$path])
            <a href="#" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="{{ $path }}"/></svg>{{ $label }}
            </a>
            @endforeach
            <p class="mt-8 px-3 text-[10px] font-bold uppercase tracking-[.18em] text-slate-600">Narzędzia</p>
            @if(in_array(auth()->user()->role->value, ['admin','manager'], true))<a href="{{ route("reports.index") }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white"><span class="size-2 rounded-full border border-slate-600"></span>Raporty</a>@endif
            @if(auth()->user()->role->value === 'admin')<a href="{{ route("admin.index") }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white"><span class="size-2 rounded-full border border-slate-600"></span>Administracja</a>@endif
            @foreach (['Integracje','Ustawienia'] as $label)
            <a href="#" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white">
                <span class="size-2 rounded-full border border-slate-600"></span>{{ $label }}
            </a>
            @endforeach
            <div class="mt-8 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-violet-500/10 p-4 ring-1 ring-inset ring-white/[0.06]">
                <p class="text-sm font-semibold text-white">Plan Pro</p>
                <p class="mt-1 text-xs leading-5 text-slate-400">Wykorzystano 68% dostępnej przestrzeni.</p>
                <div class="mt-3 h-1.5 rounded-full bg-white/10"><div class="h-full w-2/3 rounded-full bg-indigo-400"></div></div>
            </div>
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
            <div class="flex items-center gap-3">
                @include('components.theme-switcher')
                <button class="relative grid size-10 place-items-center rounded-xl bg-white/[0.04] text-slate-400 hover:text-white" aria-label="Powiadomienia">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg><span class="absolute right-2 top-2 size-2 rounded-full bg-rose-500 ring-2 ring-[#0b0d1d]"></span>
                </button>
                <div class="hidden h-8 w-px bg-white/[0.07] sm:block"></div>
                <div class="flex items-center gap-3">
                    <div class="grid size-10 place-items-center rounded-xl bg-gradient-to-br from-emerald-400 to-cyan-500 font-bold text-slate-950">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="hidden sm:block"><p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p><p class="text-[11px] text-emerald-400">{{ auth()->user()->role->label() }} · online</p></div>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="ml-1 rounded-lg p-2 text-slate-500 hover:bg-white/[0.05] hover:text-white" title="Wyloguj się"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 17l5-5-5-5M15 12H3M14 3h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5"/></svg></button></form>
                </div>
            </div>
        </header>

        <main class="p-4 sm:p-8">
            <div class="mx-auto max-w-[1500px]">
                <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                    <div><p class="text-sm font-medium text-indigo-400">Przegląd wyników</p><h1 class="mt-1 text-2xl font-bold text-white sm:text-3xl">Dzień dobry, {{ auth()->user()->name }} 👋</h1><p class="mt-2 text-sm text-slate-500">Oto najważniejsze informacje z ostatnich 30 dni.</p></div>
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-400"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12M7 10l5 5 5-5M5 21h14"/></svg>Pobierz raport</button>
                </div>

                <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ([
                        ['Łączne wizyty','423 964','+12,5%','indigo','M3 12s3-7 9-7 9 7 9 7-3 7-9 7-9-7-9-7Z M12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z'],
                        ['Nowi klienci','7 929','+8,2%','cyan','M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2 M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z M19 8v6 M22 11h-6'],
                        ['Przychód','45 141 zł','+16,4%','emerald','M3 3v18h18 M7 15l4-4 3 3 6-7'],
                        ['Konwersja','6,82%','-1,3%','violet','M12 2v20 M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6']
                    ] as [$label,$value,$change,$color,$icon])
                    <article class="group rounded-2xl border border-white/[0.06] bg-[#0d1024] p-5 transition hover:-translate-y-0.5 hover:border-white/10">
                        <div class="flex items-start justify-between"><span class="grid size-11 place-items-center rounded-xl bg-{{ $color }}-500/10 text-{{ $color }}-400"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">@foreach(explode(' M', $icon) as $i => $part)<path d="{{ $i ? 'M'.$part : $part }}"/>@endforeach</svg></span><span class="rounded-full {{ str_starts_with($change, '+') ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }} px-2.5 py-1 text-xs font-semibold">{{ $change }}</span></div>
                        <p class="mt-5 text-sm text-slate-500">{{ $label }}</p><p class="mt-1 text-2xl font-bold tracking-tight text-white">{{ $value }}</p>
                    </article>
                    @endforeach
                </section>

                <section class="mt-6 grid gap-6 xl:grid-cols-[1.7fr_1fr]">
                    <article class="rounded-2xl border border-white/[0.06] bg-[#0d1024] p-5 sm:p-6">
                        <div class="flex items-start justify-between"><div><h2 class="font-semibold text-white">Unikalni odwiedzający</h2><p class="mt-1 text-xs text-slate-500">Ruch bezpośredni i organiczny</p></div><select class="rounded-lg border border-white/[0.06] bg-[#090c1d] px-3 py-2 text-xs outline-none"><option>Ostatnie 12 miesięcy</option></select></div>
                        <div class="mt-7 flex items-center gap-5 text-xs"><span class="flex items-center gap-2"><i class="size-2 rounded-full bg-indigo-400"></i>Bezpośredni</span><span class="flex items-center gap-2"><i class="size-2 rounded-full bg-cyan-400"></i>Organiczny</span></div>
                        <div class="mt-8 flex h-64 items-end justify-between gap-2 border-b border-white/[0.06] bg-[linear-gradient(to_top,rgba(255,255,255,.04)_1px,transparent_1px)] bg-[size:100%_25%] px-2">
                            @foreach ([[38,22],[50,31],[43,28],[64,39],[55,35],[72,45],[66,42],[80,49],[74,46],[88,55],[82,51],[94,62]] as [$direct,$organic])
                            <div class="group flex h-full flex-1 items-end justify-center gap-1"><div style="height:{{ $direct }}%" class="w-2.5 rounded-t bg-indigo-500/90 transition group-hover:bg-indigo-400 sm:w-4"></div><div style="height:{{ $organic }}%" class="w-2.5 rounded-t bg-cyan-500/70 transition group-hover:bg-cyan-400 sm:w-4"></div></div>
                            @endforeach
                        </div>
                        <div class="mt-3 flex justify-between px-1 text-[10px] text-slate-600">@foreach (['Sty','Lut','Mar','Kwi','Maj','Cze','Lip','Sie','Wrz','Paź','Lis','Gru'] as $month)<span>{{ $month }}</span>@endforeach</div>
                    </article>

                    <article class="rounded-2xl border border-white/[0.06] bg-[#0d1024] p-5 sm:p-6">
                        <div class="flex items-center justify-between"><div><h2 class="font-semibold text-white">Ostatnia aktywność</h2><p class="mt-1 text-xs text-slate-500">Aktualizowane na żywo</p></div><button class="text-xs font-medium text-indigo-400">Zobacz wszystko</button></div>
                        <div class="mt-6 space-y-5">
                            @foreach ([
                                ['emerald','Nowy użytkownik','Anna Kowalska dołączyła do zespołu','2 min'],
                                ['indigo','Raport gotowy','Raport miesięczny został wygenerowany','18 min'],
                                ['amber','Płatność oczekuje','Faktura #FV-2094 wymaga uwagi','1 godz.'],
                                ['cyan','Kopia zapasowa','Automatyczna kopia zakończona','3 godz.'],
                                ['violet','Nowa wiadomość','Otrzymano wiadomość od klienta','5 godz.']
                            ] as [$color,$title,$desc,$time])
                            <div class="flex gap-3"><span class="mt-1.5 size-2.5 shrink-0 rounded-full bg-{{ $color }}-400 ring-4 ring-{{ $color }}-400/10"></span><div class="min-w-0 flex-1"><div class="flex justify-between gap-2"><p class="text-sm font-medium text-slate-200">{{ $title }}</p><span class="shrink-0 text-[10px] text-slate-600">{{ $time }}</span></div><p class="mt-1 truncate text-xs text-slate-500">{{ $desc }}</p></div></div>
                            @endforeach
                        </div>
                    </article>
                </section>

                <section class="mt-6 grid gap-6 lg:grid-cols-3">
                    <article class="rounded-2xl border border-white/[0.06] bg-[#0d1024] p-6">
                        <h2 class="font-semibold text-white">Źródła ruchu</h2><p class="mt-1 text-xs text-slate-500">Według przeglądarki</p>
                        <div class="mt-7 flex items-center justify-center"><div class="relative grid size-40 place-items-center rounded-full" style="background:conic-gradient(#6366f1 0 65%,#22d3ee 65% 88%,#a78bfa 88% 100%)"><div class="grid size-28 place-items-center rounded-full bg-[#0d1024] text-center"><div><strong class="text-2xl text-white">28,4k</strong><p class="text-[10px] text-slate-500">sesji</p></div></div></div></div>
                        <div class="mt-7 grid grid-cols-3 gap-2 text-center"><div><i class="mx-auto block size-2 rounded-full bg-indigo-500"></i><p class="mt-2 text-xs text-slate-500">Chrome</p><b class="text-sm text-white">65%</b></div><div><i class="mx-auto block size-2 rounded-full bg-cyan-400"></i><p class="mt-2 text-xs text-slate-500">Safari</p><b class="text-sm text-white">23%</b></div><div><i class="mx-auto block size-2 rounded-full bg-violet-400"></i><p class="mt-2 text-xs text-slate-500">Inne</p><b class="text-sm text-white">12%</b></div></div>
                    </article>
                    <article class="rounded-2xl border border-white/[0.06] bg-[#0d1024] p-6 lg:col-span-2">
                        <div class="flex items-center justify-between"><div><h2 class="font-semibold text-white">Ostatnie transakcje</h2><p class="mt-1 text-xs text-slate-500">Najnowsze operacje finansowe</p></div><button class="text-xs text-indigo-400">Wszystkie</button></div>
                        <div class="mt-5 overflow-x-auto"><table class="w-full min-w-[560px] text-left text-sm"><thead class="border-b border-white/[0.06] text-[10px] uppercase tracking-wider text-slate-600"><tr><th class="pb-3 font-medium">Klient</th><th class="pb-3 font-medium">Data</th><th class="pb-3 font-medium">Kwota</th><th class="pb-3 font-medium">Status</th></tr></thead><tbody>
                        @foreach ([['MK','Marek Kwiatkowski','12 sie 2026','1 280 zł','Opłacona','emerald'],['AN','Anna Nowak','11 sie 2026','840 zł','Oczekuje','amber'],['PW','Piotr Wiśniewski','10 sie 2026','2 450 zł','Opłacona','emerald'],['KL','Karolina Lis','9 sie 2026','690 zł','Anulowana','rose']] as [$initials,$name,$date,$amount,$status,$color])
                        <tr class="border-b border-white/[0.04] last:border-0"><td class="py-3.5"><div class="flex items-center gap-3"><span class="grid size-8 place-items-center rounded-lg bg-white/[0.05] text-[10px] font-bold text-slate-300">{{ $initials }}</span><span class="font-medium text-slate-200">{{ $name }}</span></div></td><td class="py-3.5 text-slate-500">{{ $date }}</td><td class="py-3.5 font-semibold text-white">{{ $amount }}</td><td class="py-3.5"><span class="rounded-full bg-{{ $color }}-500/10 px-2.5 py-1 text-xs text-{{ $color }}-400">{{ $status }}</span></td></tr>
                        @endforeach
                        </tbody></table></div>
                    </article>
                </section>
                <footer class="mt-8 flex flex-col justify-between gap-2 border-t border-white/[0.05] py-6 text-xs text-slate-600 sm:flex-row"><p>© {{ date('Y') }} {{ config('app.name', 'Nexus') }}. Wszystkie prawa zastrzeżone.</p><p>Panel administracyjny v1.0</p></footer>
            </div>
        </main>
    </div>
</div>
</x-layouts.app>
