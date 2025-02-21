<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ReservationController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            // Middleware pour les actions spécifiques
            new Middleware('permission:liste des reservations', only: ['index']),
            new Middleware('permission:detail reservation', only: ['show']),
            new Middleware('permission:supprimer reservation', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Reservation::with('user');
    
        // Filtres dynamiques
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }
    
        if ($request->filled('monthNumber')) {
            $query->where('monthNumber', $request->monthNumber);
        }
    
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
    
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('user.name', 'like', "%{$searchTerm}%")
                  ->orWhere('day', 'like', "%{$searchTerm}%")
                  ->orWhere('monthNumber', 'like', "%{$searchTerm}%")
                  ->orWhere('year', 'like', "%{$searchTerm}%");
            });
        }
    
        // Ordre et pagination
        $reservations = $query->orderBy('year')
                              ->orderBy('monthNumber')
                              ->orderBy('day')
                              ->paginate(6);
    
        // Retour des données vers la vue
        return inertia('Reservations/index', [
            'reservations' => $reservations,
            'filters' => $request->only(['day', 'monthNumber', 'year', 'search']), // Pour persister les filtres
        ]);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Reservations/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'day' => 'required|integer',
            'month' => 'required|string',
            'monthNumber' => 'required|integer',
            'dayName' => 'required|string',
            'year' => 'required|integer',
            'startClock' => 'required|string',
            'endClock' => 'required|string',
            'user_id' => 'nullable|string',
        ]);

        $monthNumber = $validated['monthNumber'] + 1;
        $currentDate = new \DateTime();
        $reservationEndDate = new \DateTime("{$validated['year']}-{$monthNumber}-{$validated['day']} {$validated['endClock']}");

        if ($currentDate > $reservationEndDate) {
            return redirect()->back()->with('message', "La réservation ne peut pas être effectuée pour une date déjà passée.");
        }

        if ($validated['startClock'] >= $validated['endClock']) {
            return redirect()->back()->with("message", "Le début de l'heure doit être inférieur à la fin.");
        }

        $validated['user_id'] = Auth::user()->id;

        $existingReservation = Reservation::where('day', $request->day)
            ->where('monthNumber', $request->monthNumber)
            ->where('year', $request->year)
            ->where(function ($query) use ($request) {
                $query->whereBetween('startClock', [$request->startClock, $request->endClock])
                      ->orWhereBetween('endClock', [$request->startClock, $request->endClock]);
            })
            ->exists();

        if ($existingReservation) {
            return redirect()->back()->with('message', 'Ce créneau est déjà réservé.');
        }

        Reservation::create($validated);
        return redirect()->back()->with('success', 'Réservation effectuée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reservation = Reservation::with('user')->findOrFail($id);
        return inertia('Reservations/show', ['reservation' => $reservation]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        return inertia('Reservations/edit', ['reservation' => $reservation]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'day' => 'required|integer',
            'month' => 'required|string',
            'monthNumber' => 'required|integer',
            'dayName' => 'required|string',
            'year' => 'required|integer',
            'startClock' => 'required|string',
            'endClock' => 'required|string',
        ]);

        $reservation = Reservation::findOrFail($id);

        $monthNumber = $validated['monthNumber'] + 1;
        $currentDate = new \DateTime();
        $reservationEndDate = new \DateTime("{$validated['year']}-{$monthNumber}-{$validated['day']} {$validated['endClock']}");

        if ($currentDate > $reservationEndDate) {
            return redirect()->back()->with('message', "La réservation ne peut pas être effectuée pour une date déjà passée.");
        }

        if ($validated['startClock'] >= $validated['endClock']) {
            return redirect()->back()->with("message", "Le début de l'heure doit être inférieur à la fin.");
        }

        $existingReservation = Reservation::where('day', $request->day)
            ->where('monthNumber', $request->monthNumber)
            ->where('year', $request->year)
            ->where(function ($query) use ($request) {
                $query->whereBetween('startClock', [$request->startClock, $request->endClock])
                      ->orWhereBetween('endClock', [$request->startClock, $request->endClock]);
            })
            ->where('id', '!=', $id)
            ->exists();

        if ($existingReservation) {
            return redirect()->back()->with('message', 'Ce créneau est déjà réservé.');
        }

        $reservation->update($validated);
        return redirect()->route('reservations.index')->with('success', 'Réservation mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Réservation supprimée avec succès.');
    }

    /**
     * Display reservations for a specific date.
     */
    public function dateReservation(Request $request)
{
    $reservations = Reservation::where('day', $request->input("date"))
                                ->where('month', $request->input("month"))
                                ->where('year', $request->input("year"))
                                ->with('user')
                                ->orderBy('year')
                                ->orderBy('monthNumber')
                                ->orderBy('day')
                                ->get();

    return inertia('Reservations/dateReservation', [
        'reservations' => $reservations,
        'day' => $request->input("date"),
        'month' => $request->input("month"),
        'year' => $request->input("year"),
        'monthNumber' => $request->input("monthNumber"),
    ]);
}

}
