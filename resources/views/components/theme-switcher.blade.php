<div class="relative" data-theme-picker>
    <button type="button" data-theme-toggle class="inline-flex h-10 items-center gap-2 rounded-xl bg-white/[0.04] px-3 text-sm font-medium text-slate-300 transition hover:bg-white/[0.07] hover:text-white" aria-label="Zmień wygląd strony" aria-expanded="false">
        <svg data-theme-icon="light" class="hidden size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.42 1.42M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.42-1.42M17.66 6.34l1.41-1.41"/></svg>
        <svg data-theme-icon="dark" class="hidden size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/></svg>
        <svg data-theme-icon="system" class="hidden size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
        <span data-theme-label class="hidden lg:inline">System</span>
        <svg class="hidden size-3.5 text-slate-500 lg:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
    </button>
    <div data-theme-menu class="absolute right-0 z-50 mt-2 hidden w-44 overflow-hidden rounded-xl border border-white/[0.08] bg-[#0d1024] p-1.5 shadow-2xl shadow-black/30">
        @foreach ([['light', 'Jasny'], ['dark', 'Ciemny'], ['system', 'System']] as [$value, $label])
            <button type="button" data-theme-option="{{ $value }}" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm text-slate-300 transition hover:bg-white/[0.06] hover:text-white">
                <span>{{ $label }}</span>
                <svg data-theme-check="{{ $value }}" class="hidden size-4 text-indigo-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m5 12 4 4L19 6"/></svg>
            </button>
        @endforeach
    </div>
</div>
