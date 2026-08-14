@if($warehouseTreeProducts->isNotEmpty())
<div class="ml-5 border-l border-white/[0.08] pl-3">
    @foreach($warehouseTreeProducts as $treeProduct)
    @php($currentProduct = request()->route('product'))
    @php($currentFlavor = request()->route('flavor'))
    <details class="group/tree" @if($currentProduct?->id === $treeProduct->id) open @endif>
        <summary class="flex cursor-pointer list-none items-center gap-2 rounded-lg px-2 py-2 text-xs transition hover:bg-white/[0.04] hover:text-white">
            <svg class="size-3 shrink-0 transition group-open/tree:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            <a href="{{ route('warehouse.flavors',$treeProduct) }}" class="min-w-0 flex-1 truncate {{ $currentProduct?->id === $treeProduct->id ? 'font-semibold text-indigo-300':'text-slate-400' }}">{{ $treeProduct->name }}</a>
            <span class="text-[9px] text-slate-600">{{ $treeProduct->flavors->count() }}</span>
        </summary>
        <div class="ml-3 border-l border-white/[0.06] pl-3">
            @forelse($treeProduct->flavors as $treeFlavor)
            <a href="{{ route('warehouse.containers',[$treeProduct,$treeFlavor]) }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-[11px] transition hover:bg-white/[0.04] hover:text-white {{ $currentFlavor?->id === $treeFlavor->id ? 'bg-indigo-500/10 font-semibold text-indigo-300':'text-slate-500' }}"><span class="size-1.5 rounded-full bg-indigo-400/60"></span><span class="truncate">{{ $treeFlavor->name }}</span></a>
            @empty
            <span class="block px-2 py-1.5 text-[10px] italic text-slate-700">Brak smaków</span>
            @endforelse
        </div>
    </details>
    @endforeach
</div>
@endif
