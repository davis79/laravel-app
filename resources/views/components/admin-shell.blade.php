@props(['title', 'description' => null])

<x-layouts.app :title="$title.' — '.config('app.name')">
    <div class="min-h-screen bg-[#060818] text-slate-300">
        <aside class="fixed inset-y-0 left-0 hidden w-64 border-r border-white/[0.06] bg-[#0d1024] lg:block">
            <div class="flex h-20 items-center gap-3 border-b border-white/[0.06] px-6">
                <img src="{{ asset('images/danone-logo.png') }}" alt="Danone" class="size-11 shrink-0 object-contain">
                <div><p class="text-lg font-bold tracking-wide text-white">Danone</p><p class="text-[10px] uppercase tracking-[.18em] text-slate-500">Panel zarządzania</p></div>
            </div>
            <nav class="px-4 py-6">
                <p class="px-3 text-[10px] font-bold uppercase tracking-[.18em] text-slate-600">Administracja</p>
                <a href="{{ route('dashboard') }}" class="mt-3 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 transition hover:bg-white/[0.04] hover:text-white">← Pulpit</a>
                <a href="{{ route('admin.users.index') }}" class="mt-1 flex items-center gap-3 rounded-xl bg-indigo-500/15 px-3 py-3 text-sm font-semibold text-indigo-300 ring-1 ring-inset ring-indigo-500/20">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM19 8v6M22 11h-6"/></svg>
                    Użytkownicy
                </a>
            </nav>
        </aside>
        <div class="lg:pl-64">
            <header class="flex h-20 items-center justify-between border-b border-white/[0.06] bg-[#090c1d] px-4 sm:px-8">
                <a href="{{ route('dashboard') }}" class="text-sm text-slate-400 lg:hidden">← Pulpit</a>
                <div class="ml-auto">@include('components.user-toolbar')</div>
            </header>
            <main class="p-4 sm:p-8">
                <div class="mx-auto max-w-7xl">
                    <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                        <div><p class="text-sm font-medium text-indigo-400">Administracja</p><h1 class="mt-1 text-2xl font-bold text-white sm:text-3xl">{{ $title }}</h1>@if($description)<p class="mt-2 text-sm text-slate-500">{{ $description }}</p>@endif</div>
                        {{ $actions ?? '' }}
                    </div>
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</x-layouts.app>
