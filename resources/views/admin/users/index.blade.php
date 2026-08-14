<x-admin-shell title="Użytkownicy" description="Zarządzaj kontami i poziomami dostępu.">
    <x-slot:actions><a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 hover:bg-indigo-400">+ Dodaj użytkownika</a></x-slot:actions>

    @if(session('status'))<div class="mb-5 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">{{ session('status') }}</div>@endif

    <section class="overflow-hidden rounded-2xl border border-white/[0.06] bg-[#0d1024]">
        <div class="border-b border-white/[0.06] p-4 sm:p-5">
            <form class="flex gap-3" method="GET">
                <input name="search" value="{{ $search }}" placeholder="Szukaj po nazwie lub e-mailu..." class="min-w-0 flex-1 rounded-xl border border-white/[0.08] bg-[#080b1b] px-4 py-2.5 text-sm text-white outline-none focus:border-indigo-500">
                <button class="rounded-xl border border-white/10 px-4 py-2.5 text-sm font-medium text-slate-300 hover:bg-white/5">Szukaj</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="border-b border-white/[0.06] text-[10px] uppercase tracking-wider text-slate-600"><tr><th class="px-5 py-4 font-medium">Użytkownik</th><th class="px-5 py-4 font-medium">Rola</th><th class="px-5 py-4 font-medium">Utworzono</th><th class="px-5 py-4 text-right font-medium">Akcje</th></tr></thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b border-white/[0.04] last:border-0 hover:bg-white/[0.02]">
                        <td class="px-5 py-4"><div class="flex items-center gap-3"><span class="grid size-10 place-items-center rounded-xl bg-indigo-500/10 font-bold text-indigo-300">{{ strtoupper(substr($user->name, 0, 1)) }}</span><div><p class="font-semibold text-white">{{ $user->name }} @if(auth()->id() === $user->id)<span class="text-xs font-normal text-slate-600">(Ty)</span>@endif</p><p class="mt-0.5 text-xs text-slate-500">{{ $user->email }}</p></div></div></td>
                        <td class="px-5 py-4"><span @class(['rounded-full px-2.5 py-1 text-xs font-medium','bg-violet-500/10 text-violet-300' => $user->role === \App\Enums\UserRole::Admin,'bg-cyan-500/10 text-cyan-300' => $user->role === \App\Enums\UserRole::Manager,'bg-slate-500/10 text-slate-400' => $user->role === \App\Enums\UserRole::User])>{{ $user->role->label() }}</span></td>
                        <td class="px-5 py-4 text-slate-500">{{ $user->created_at->format('d.m.Y') }}</td>
                        <td class="px-5 py-4 text-right"><a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg border border-white/[0.08] px-3 py-2 text-xs font-medium text-slate-300 hover:border-indigo-500/40 hover:text-indigo-300">Edytuj</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-16 text-center text-slate-500">Nie znaleziono użytkowników.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())<div class="border-t border-white/[0.06] px-5 py-4">{{ $users->links() }}</div>@endif
    </section>
</x-admin-shell>
