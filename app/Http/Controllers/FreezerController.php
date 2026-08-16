<?php

namespace App\Http\Controllers;

use App\Models\Freezer;
use App\Models\VaccineType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FreezerController extends Controller
{
    public function index(): View
    {
        $freezers = Freezer::query()
            ->with(['currentAssignment.vaccineType', 'latestTemperatureCheck.recorder', 'latestCleaning.recorder'])
            ->orderBy('number')
            ->get();

        $temperatureDue = $freezers->filter(fn (Freezer $freezer) => ! $freezer->temperature_checked_today)->count();
        $cleaningDue = $freezers->filter(fn (Freezer $freezer) => ! $freezer->cleaning_is_valid)->count();

        return view('freezers.index', compact('freezers', 'temperatureDue', 'cleaningDue'));
    }

    public function store(Request $request): RedirectResponse
    {
        Freezer::create($request->validate([
            'number' => ['required', 'string', 'max:255', 'unique:freezers,number'],
        ]));

        return back()->with('status', 'Zamrażarka została dodana.');
    }

    public function show(Freezer $freezer): View
    {
        $freezer->load([
            'currentAssignment.vaccineType',
            'vaccineAssignments' => fn ($query) => $query->with(['vaccineType', 'recorder'])->latest('started_at'),
            'temperatureChecks' => fn ($query) => $query->with('recorder')->latest('checked_at'),
            'cleanings' => fn ($query) => $query->with('recorder')->latest('cleaned_at'),
            'latestTemperatureCheck.recorder',
            'latestCleaning.recorder',
        ]);
        $types = VaccineType::query()->orderBy('name')->get();

        return view('freezers.show', compact('freezer', 'types'));
    }

    public function assignType(Request $request, Freezer $freezer): RedirectResponse
    {
        $validated = $request->validate([
            'vaccine_type_id' => ['required', Rule::exists('vaccine_types', 'id')],
            'started_at' => ['required', 'date', 'before_or_equal:today'],
        ]);

        DB::transaction(function () use ($validated, $freezer, $request) {
            $locked = Freezer::query()->lockForUpdate()->findOrFail($freezer->id);
            $current = $locked->vaccineAssignments()->whereNull('ended_at')->latest('started_at')->first();
            $startedAt = Carbon::parse($validated['started_at'])->startOfDay();

            if ($current && $current->started_at->gt($startedAt)) {
                throw ValidationException::withMessages([
                    'started_at' => 'Nowe przypisanie nie może rozpocząć się przed aktualnym okresem.',
                ]);
            }

            if ($current) {
                $current->update(['ended_at' => $startedAt->toDateString()]);
            }

            $locked->vaccineAssignments()->create([
                'vaccine_type_id' => $validated['vaccine_type_id'],
                'started_at' => $startedAt->toDateString(),
                'recorded_by' => $request->user()->id,
            ]);
        });

        return back()->with('status', 'Typ szczepionki został przypisany do zamrażarki.');
    }

    public function endAssignment(Request $request, Freezer $freezer): RedirectResponse
    {
        $validated = $request->validate([
            'ended_at' => ['required', 'date', 'before_or_equal:today'],
        ]);

        DB::transaction(function () use ($validated, $freezer) {
            $locked = Freezer::query()->lockForUpdate()->findOrFail($freezer->id);
            $current = $locked->vaccineAssignments()->whereNull('ended_at')->latest('started_at')->first();

            if (! $current) {
                throw ValidationException::withMessages(['ended_at' => 'Zamrażarka jest już oznaczona jako pusta.']);
            }

            $endedAt = Carbon::parse($validated['ended_at'])->startOfDay();
            if ($current->started_at->gt($endedAt)) {
                throw ValidationException::withMessages(['ended_at' => 'Data opróżnienia nie może poprzedzać daty przypisania.']);
            }

            $current->update(['ended_at' => $endedAt->toDateString()]);
        });

        return back()->with('status', 'Zamrażarka została oznaczona jako pusta.');
    }

    public function storeTemperature(Request $request, Freezer $freezer): RedirectResponse
    {
        $validated = $request->validate([
            'temperature_c' => ['required', 'numeric', 'between:-100,50', 'decimal:0,2'],
            'checked_at' => ['required', 'date', 'before_or_equal:now'],
        ]);

        $freezer->temperatureChecks()->create($validated + ['recorded_by' => $request->user()->id]);

        return back()->with('status', 'Kontrola temperatury została zapisana.');
    }

    public function storeCleaning(Request $request, Freezer $freezer): RedirectResponse
    {
        $validated = $request->validate([
            'cleaned_at' => ['required', 'date', 'before_or_equal:now'],
        ]);
        $cleanedAt = Carbon::parse($validated['cleaned_at']);

        $freezer->cleanings()->create([
            'cleaned_at' => $cleanedAt,
            'valid_until' => $cleanedAt->copy()->addDays(30)->toDateString(),
            'recorded_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Mycie zamrażarki zostało zapisane. Termin ważności wynosi 30 dni.');
    }
}
