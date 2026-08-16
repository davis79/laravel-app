<?php

namespace App\Http\Controllers;

use App\Models\VaccineLot;
use App\Models\VaccineType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VaccineController extends Controller
{
    public function index(): View
    {
        $lots = VaccineLot::query()
            ->withSum('usages', 'quantity_kg')
            ->orderByRaw('(weight_kg - COALESCE((SELECT SUM(quantity_kg) FROM vaccine_usages WHERE vaccine_usages.vaccine_lot_id = vaccine_lots.id), 0)) <= 0')
            ->latest('received_at')
            ->paginate(20);

        $totalWeight = (float) VaccineLot::sum('weight_kg');
        $totalUsed = (float) DB::table('vaccine_usages')->sum('quantity_kg');
        $remainingWeight = max(0, $totalWeight - $totalUsed);
        $types = VaccineType::query()->orderBy('name')->get();

        return view('vaccines.index', compact('lots', 'types', 'totalWeight', 'totalUsed', 'remainingWeight'));
    }

    public function store(Request $request): RedirectResponse
    {
        VaccineLot::create($request->validate([
            'type' => ['required', 'string', 'max:255', Rule::exists('vaccine_types', 'name')],
            'lot_number' => ['required', 'string', 'max:255', 'unique:vaccine_lots,lot_number'],
            'received_at' => ['required', 'date'],
            'weight_kg' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
        ]));

        return back()->with('status', 'Szczepionka została przyjęta do magazynu.');
    }

    public function storeType(Request $request): RedirectResponse
    {
        VaccineType::create($request->validateWithBag('type', [
            'name' => ['required', 'string', 'max:255', 'unique:vaccine_types,name'],
        ]));

        return back()->with('status', 'Nowy typ szczepionki został dodany.');
    }

    public function show(VaccineLot $lot): View
    {
        $lot->load(['usages.recorder'])->loadSum('usages', 'quantity_kg');

        return view('vaccines.show', compact('lot'));
    }

    public function storeUsage(Request $request, VaccineLot $lot): RedirectResponse
    {
        $validated = $request->validate([
            'production_number' => ['required', 'string', 'max:255'],
            'quantity_kg' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
            'used_at' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($validated, $lot, $request) {
            $locked = VaccineLot::query()->lockForUpdate()->findOrFail($lot->id);
            $used = (float) $locked->usages()->sum('quantity_kg');
            $remaining = (float) $locked->weight_kg - $used;

            if ($remaining <= 0) {
                throw ValidationException::withMessages([
                    'quantity_kg' => 'Ten lot jest nieaktywny — cała ilość została pobrana.',
                ]);
            }

            if ((float) $validated['quantity_kg'] > $remaining) {
                throw ValidationException::withMessages([
                    'quantity_kg' => 'Ilość przekracza pozostały stan szczepionki.',
                ]);
            }

            $locked->usages()->create($validated + ['recorded_by' => $request->user()->id]);
        });

        return back()->with('status', 'Pobranie szczepionki zostało zapisane.');
    }
}
