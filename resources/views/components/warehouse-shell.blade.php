@props(['title', 'description' => null])
<x-layouts.app :title="$title.' — '.config('app.name')">
<div class="min-h-screen bg-[#060818] text-slate-300">
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full border-r border-white/[0.06] bg-[#0d1024] transition-transform duration-300 lg:translate-x-0">
        <div class="flex h-20 items-center gap-3 border-b border-white/[0.06] px-6">
            <img src="{{ asset('images/danone-logo.png') }}" alt="Danone" class="size-11 shrink-0 object-contain">
            <div><p class="text-lg font-bold tracking-wide text-white">Danone</p><p class="text-[10px] uppercase tracking-[.18em] text-slate-500">Panel zarządzania</p></div>
        </div>
        <nav class="h-[calc(100vh-5rem)] overflow-y-auto px-4 py-6">
            <p class="px-3 text-[10px] font-bold uppercase tracking-[.18em] text-slate-600">Menu główne</p>
            <a href="{{ route('dashboard') }}" class="mt-3 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 hover:bg-white/[0.04] hover:text-white"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/></svg>Pulpit</a>
            <a href="{{ route('warehouse.products') }}" class="mt-1 flex items-center gap-3 rounded-xl bg-indigo-500/15 px-3 py-3 text-sm font-semibold text-indigo-300 ring-1 ring-inset ring-indigo-500/20"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16v13H4zM7 7V4h10v3M8 11h8M8 15h5"/></svg>Magazyn owocowy</a>
            @include("components.warehouse.tree")
            @if(auth()->user()->role->value === 'admin')<a href="{{ route('admin.users.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 hover:bg-white/[0.04] hover:text-white"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/></svg>Użytkownicy</a>@endif
            <a href="#" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 hover:bg-white/[0.04] hover:text-white"><span class="size-2 rounded-full border border-slate-600"></span>Kalendarz</a>
            <a href="#" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 hover:bg-white/[0.04] hover:text-white"><span class="size-2 rounded-full border border-slate-600"></span>Faktury</a>
            <p class="mt-8 px-3 text-[10px] font-bold uppercase tracking-[.18em] text-slate-600">Narzędzia</p>
            @if(in_array(auth()->user()->role->value,['admin','manager'],true))<a href="{{ route('reports.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 hover:bg-white/[0.04] hover:text-white"><span class="size-2 rounded-full border border-slate-600"></span>Raporty</a>@endif
            @if(auth()->user()->role->value === 'admin')<a href="{{ route('admin.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 hover:bg-white/[0.04] hover:text-white"><span class="size-2 rounded-full border border-slate-600"></span>Administracja</a>@endif
        </nav>
    </aside>
    <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-black/60 lg:hidden"></div>
    <div class="lg:pl-64">
        <header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-white/[0.06] bg-[#060818]/90 px-4 backdrop-blur-xl sm:px-8">
            <button id="sidebar-toggle" class="grid size-10 place-items-center rounded-xl bg-white/[0.05] lg:hidden"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
            <div class="hidden items-center gap-3 rounded-xl bg-white/[0.04] px-4 py-2.5 sm:flex"><svg class="size-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input class="w-44 bg-transparent text-sm outline-none" placeholder="Szukaj w panelu..."></div>
            <div class="ml-auto flex items-center gap-3">@include('components.theme-switcher')<div class="grid size-10 place-items-center rounded-xl bg-gradient-to-br from-emerald-400 to-cyan-500 font-bold text-slate-950">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div><div class="hidden sm:block"><p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p><p class="text-[11px] text-emerald-400">{{ auth()->user()->role->label() }} · online</p></div></div>
        </header>
        <main class="p-4 sm:p-8"><div class="mx-auto max-w-[1500px]">
            <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end"><div><div class="mb-3 flex gap-2 text-xs text-slate-500"><a href="{{ route('warehouse.products') }}">Magazyn owocowy</a>{{ $breadcrumbs ?? '' }}</div><h1 class="text-2xl font-bold text-white sm:text-3xl">{{ $title }}</h1>@if($description)<p class="mt-2 text-sm text-slate-500">{{ $description }}</p>@endif</div>{{ $actions ?? '' }}</div>
            @if(session('status'))<div class="mb-5 rounded-xl bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">{{ session('status') }}</div>@endif
            {{ $slot }}
        </div></main>
    </div>
</div>
</x-layouts.app>
