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
        try {
            // Validation des données
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'day' => 'required|integer',
                'month' => 'required|string',
                'monthNumber' => 'required|integer',
                'dayName' => 'required|string',
                'year' => 'required|integer',
                'startClock' => 'required|string',
                'endClock' => 'required|string',
                'user_id' => 'nullable|string',
            ]);
    
            // Calcul de la date de fin
            $monthNumber = $validated['monthNumber'] + 1;
            $currentDate = new \DateTime();
            $reservationEndDate = new \DateTime("{$validated['year']}-{$monthNumber}-{$validated['day']} {$validated['endClock']}");
    
            if ($currentDate > $reservationEndDate) {
                return redirect()->back()->withInput()->with('message', "La réservation ne peut pas être effectuée pour une date déjà passée.");
            }
    
            if ($validated['startClock'] >= $validated['endClock']) {
                return redirect()->back()->withInput()->with('message', "L'heure de début doit être antérieure à celle de fin.");
            }
    
            $validated['user_id'] = Auth::id();
    
            // Vérifie les chevauchements
            $existingReservation = Reservation::where('day', $validated['day'])
                ->where('monthNumber', $validated['monthNumber'])
                ->where('year', $validated['year'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('startClock', [$validated['startClock'], $validated['endClock']])
                          ->orWhereBetween('endClock', [$validated['startClock'], $validated['endClock']]);
                })
                ->exists();
    
            if ($existingReservation) {
                return redirect()->back()->withInput()->with('message', 'Ce créneau est déjà réservé.');
            }
    
            // Création de la réservation
            Reservation::create($validated);
    
            return redirect()->back()->with('success', 'Réservation effectuée avec succès.');
    
        } catch (\Exception $e) {
            // Log optionnel (à activer si besoin)
            // Log::error("Erreur de réservation : " . $e->getMessage());
    
            return redirect()->back()->withInput()->withErrors([
                'general' => 'Une erreur est survenue lors de la réservation. Veuillez réessayer.',
            ]);
        }
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
        try {
            // Validation avec des longueurs maximales
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'day' => 'required|integer',
                'month' => 'required|string',
                'monthNumber' => 'required|integer',
                'dayName' => 'required|string',
                'year' => 'required|integer',
                'startClock' => 'required|string',
                'endClock' => 'required|string',
            ]);
    
            $reservation = Reservation::findOrFail($id);
    
            // Vérification de la date
            $monthNumber = $validated['monthNumber'] + 1;
            $currentDate = new \DateTime();
            $reservationEndDate = new \DateTime("{$validated['year']}-{$monthNumber}-{$validated['day']} {$validated['endClock']}");
    
            if ($currentDate > $reservationEndDate) {
                return redirect()->back()->withInput()->with('message', "La réservation ne peut pas être effectuée pour une date déjà passée.");
            }
    
            if ($validated['startClock'] >= $validated['endClock']) {
                return redirect()->back()->withInput()->with("message", "L'heure de début doit être antérieure à l'heure de fin.");
            }
    
            // Vérifie les chevauchements (hors cette réservation)
            $existingReservation = Reservation::where('day', $validated['day'])
                ->where('monthNumber', $validated['monthNumber'])
                ->where('year', $validated['year'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('startClock', [$validated['startClock'], $validated['endClock']])
                          ->orWhereBetween('endClock', [$validated['startClock'], $validated['endClock']]);
                })
                ->where('id', '!=', $id)
                ->exists();
    
            if ($existingReservation) {
                return redirect()->back()->withInput()->with('message', 'Ce créneau est déjà réservé.');
            }
    
            // Mise à jour
            $reservation->update($validated);
    
            return redirect()->route('reservations.index')->with('success', 'Réservation mise à jour avec succès.');
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gestion des erreurs de validation (retourne automatiquement avec les erreurs)
            return redirect()->back()->withErrors($e->validator)->withInput();
    
        } catch (\Exception $e) {
            // Autres erreurs imprévues
            return redirect()->back()->withInput()->withErrors([
                'general' => 'Une erreur est survenue lors de la mise à jour. Veuillez réessayer.',
            ]);
        }
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
