<x-warehouse-shell :title="$message->subject" description="Treść odebranej wiadomości." root-label="Ustawienia" root-route="settings.index">
    <x-slot:breadcrumbs><span>/</span><span>Wiadomość</span></x-slot:breadcrumbs>
    <article class="max-w-4xl rounded-2xl border border-white/[0.07] bg-white/[0.035] p-6 sm:p-8">
        <div class="flex flex-col justify-between gap-3 border-b border-white/[0.07] pb-6 sm:flex-row">
            <div><p class="text-sm text-slate-500">Nadawca</p><p class="mt-1 font-semibold text-white">{{ $message->sender?->name ?? 'System' }}</p></div>
            <div class="sm:text-right"><p class="text-sm text-slate-500">Otrzymano</p><p class="mt-1 text-sm text-slate-300">{{ $message->created_at->format('d.m.Y H:i') }}</p></div>
        </div>
        <div class="whitespace-pre-line py-7 leading-7 text-slate-300">{{ $message->body }}</div>
        <a href="{{ route('settings.index') }}#wiadomosci" class="inline-flex rounded-xl border border-white/[0.1] px-4 py-2.5 text-sm text-slate-300 hover:bg-white/[0.05] hover:text-white">← Wróć do wiadomości</a>
    </article>
</x-warehouse-shell>
