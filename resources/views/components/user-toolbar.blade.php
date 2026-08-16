<div class="flex items-center gap-3">
    @include('components.theme-switcher')
    <a href="{{ route('settings.index') }}#wiadomosci" class="relative grid size-10 place-items-center rounded-xl bg-white/[0.04] text-slate-400 hover:text-white" aria-label="Wiadomości">
        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg>
        @if(auth()->user()->receivedMessages()->whereNull('read_at')->exists())<span class="absolute right-2 top-2 size-2 rounded-full bg-rose-500 ring-2 ring-[#202124]"></span>@endif
    </a>
    <div class="hidden h-8 w-px bg-white/[0.07] sm:block"></div>
    <div class="flex items-center gap-3">
        <div class="grid size-10 place-items-center rounded-xl bg-gradient-to-br from-emerald-400 to-cyan-500 font-bold text-slate-950">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="hidden sm:block">
            <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
            <p class="text-[11px] text-emerald-400">{{ auth()->user()->role->label() }} · online</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="ml-1 rounded-lg p-2 text-slate-500 hover:bg-white/[0.05] hover:text-white" title="Wyloguj się" aria-label="Wyloguj się">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 17l5-5-5-5M15 12H3M14 3h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5"/></svg>
            </button>
        </form>
    </div>
</div>
