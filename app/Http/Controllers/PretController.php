<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pret;
use App\Models\Notice;
use App\Models\Lecteur;
use App\Models\NoticeExemplaire;
use App\Models\Statistique;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PretController extends Controller

{
    public function store(Request $request)
{
    // Validate that an exemplaire is selected and exists
    $request->validate([
        'exemplaire_id' => 'required|exists:exemplaires,exp_id',
    ]);

    // Get the authenticated lecteur ID
    $lecteurId = auth()->id();

    // Ensure the user is logged in
    if (!$lecteurId) {
        return redirect()->back()->with('error', 'Vous devez être connecté pour faire une demande de prêt.');
    }

    // Insert the new loan request
    \App\Models\Pret::create([
        'lec_id' => $lecteurId,
        'exp_id' => $request->exemplaire_id,
        'date_demande' => now(),
        'statut' => 'en_attente', // Use the correct value used in your system
    ]);

    return redirect()->back()->with('success', 'Votre demande de prêt a été envoyée avec succès.');
}

    /**
     * Affiche la liste des prêts.
     */
    public function index(Request $request)
    {
        // Met à jour automatiquement les statuts "en_cours" vers "en_retard" si la date de retour est dépassée
    Pret::where('statut', 'en_cours')
        ->whereDate('date_retour', '<', Carbon::today())
        ->update(['statut' => 'en_retard']);
        
        $query = Pret::with(['lecteur', 'exemplaire.notice']);
        
        // Filtres
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('lecteur', function ($q) use ($search) {
                $q->where('lec_nom', 'like', "%{$search}%")
                  ->orWhere('lec_prenom', 'like', "%{$search}%");
            })->orWhereHas('exemplaire.notice', function ($q) use ($search) {
                $q->where('doc_titre', 'like', "%{$search}%")
                  ->orWhere('doc_auteur', 'like', "%{$search}%");
            })->orWhere('pret_id', 'like', "%{$search}%");
        }
        
        if ($request->filled('statut') && $request->input('statut') !== 'tous') {
            $query->where('statut', $request->input('statut'));
        }
        
        $prets = $query->orderBy('date_pret', 'desc')
        ->where('statut', '=', 'en_cours')
        ->paginate(10);
        
        return view('dashboard.loans', compact('prets'));
    }

    /**
     * Affiche le formulaire de création d'un prêt.
     */
    public function create()
    {
        $lecteurs = Lecteur::orderBy('lec_nom')->get();
        $notices = Notice::orderBy('doc_titre')->get();
        
        return view('dashboard.create', compact('lecteurs', 'notices'));
    }


    /**
     * Affiche les détails d'un prêt.
     */
    public function show($id)
    {
        $pret = Pret::with(['lecteur', 'exemplaire.notice'])->findOrFail($id);
        
        return view('prets.show', compact('pret'));
    }


    /**
     * Met à jour un prêt existant.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date_retour' => 'required|date|after:today',
            'statut' => 'required|in:en_cours,en_retard,renouvele',
        ]);
        
        $pret = Pret::findOrFail($id);
        $pret->date_retour = $request->date_retour;
        $pret->statut = $request->statut;
        $pret->save();
        
        return redirect()->route('prets.index')->with('success', 'Prêt mis à jour avec succès.');
    }

    /**
     * Supprime un prêt.
     */
    public function destroy($id)
    {
        $pret = Pret::findOrFail($id);
        
        // Libère l'exemplaire si le prêt n'est pas retourné
        if (!$pret->date_retour_reelle) {
            $exemplaire = $pret->exemplaire;
            $exemplaire->etat = 'disponible';
            $exemplaire->save();
        }
        
        $pret->delete();
        
        return redirect()->route('prets.index')->with('success', 'Prêt supprimé avec succès.');
    }
    
    /**
     * Traite le retour d'un livre.
     */
    
    
    
    /**
     * Renouvelle un prêt.
     */
    public function renouveler($id)
    {
        $pret = Pret::findOrFail($id);
        
        if ($pret->statut === 'en_retard') {
            return back()->with('error', 'Impossible de renouveler un prêt en retard.');
        }
        
        if ($pret->renouveler()) {
            // Enregistre la statistique
            Statistique::enregistrer('renouvellement');
            
            return redirect()->route('prets.index')->with('success', 'Prêt renouvelé avec succès.');
        }
        
        return back()->with('error', 'Une erreur est survenue lors du renouvellement.');
    }
  



    

public static function updateStatutsRetard()
{
    // Met à jour automatiquement les statuts "en_cours" vers "en_retard" si la date de retour est dépassée
    Pret::where('statut', 'en_cours')
        ->whereDate('date_retour', '<', Carbon::today())
        ->update(['statut' => 'en_retard']);
}

    
    /**
     * Génère un rapport sur les prêts.
     */
    public function rapport(Request $request)
    {
        $debut = $request->input('date_debut', now()->subMonth()->format('Y-m-d'));
        $fin = $request->input('date_fin', now()->format('Y-m-d'));
        
        $stats = [
            'totalPrets' => Pret::whereBetween('date_pret', [$debut, $fin])->count(),
            'pretsEnCours' => Pret::whereIn('statut', ['en_cours', 'renouvele'])->count(),
            'pretsRetournes' => Pret::where('statut', 'retourne')
                                   ->whereBetween('date_retour_reelle', [$debut, $fin])
                                   ->count(),
            'pretsEnRetard' => Pret::where('statut', 'en_retard')->count(),
            'renouvellements' => Pret::where('statut', 'renouvele')
                                    ->whereBetween('updated_at', [$debut, $fin])
                                    ->count(),
        ];
        
        $livresPopulaires = Pret::with('exemplaire.notice')
                               ->whereBetween('date_pret', [$debut, $fin])
                               ->get()
                               ->groupBy('exemplaire.doc_id')
                               ->map(function ($group) {
                                   return [
                                       'count' => $group->count(),
                                       'notice' => $group->first()->exemplaire->notice
                                   ];
                               })
                               ->sortByDesc('count')
                               ->take(5)
                               ->values();
                               
        return view('prets.rapport', compact('stats', 'livresPopulaires', 'debut', 'fin'));
    }

    
   

public function returns(Request $request)
{
    // Update statuses
    Pret::where('statut', 'en_cours')
        ->whereDate('date_retour', '<', Carbon::today())
        ->update(['statut' => 'en_retard']);

    $query = Pret::with(['lecteur', 'exemplaire.notice'])
               ->where('statut', 'en_retard');

    // Add search filter
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->whereHas('lecteur', function ($q) use ($search) {
            $q->where('lec_nom', 'like', "%{$search}%")
              ->orWhere('lec_prenom', 'like', "%{$search}%");
        })->orWhereHas('exemplaire.notice', function ($q) use ($search) {
            $q->where('doc_titre', 'like', "%{$search}%")
              ->orWhere('doc_auteur', 'like', "%{$search}%");
        });
    }

    $retards = $query->orderBy('date_retour', 'asc')
                   ->paginate(10);

    return view('dashboard.returns', compact('retards'));
}


    public function markReturned($id)
{
    DB::beginTransaction();
    
    try {
        $pret = Pret::with(['exemplaire', 'lecteur'])->findOrFail($id);
        
        // Update loan status
        $pret->statut = 'retourne';
        $pret->date_retour_reelle = now();
        $pret->save();
        
        // Update exemplaire status if it exists
        if ($pret->exemplaire) {
            $pret->exemplaire->etat = 'disponible';
            $pret->exemplaire->save();
        }
        
        // Create return record
        \App\Models\Returned::create([
            'is_admin' => $pret->lecteur->is_admin ?? 0,
            'doc_titre' => $pret->exemplaire->notice->doc_titre ?? 'Inconnu',
        ]);
        
        DB::commit();
        
        return redirect()->route('prets.index')
            ->with('success', 'Le prêt a été marqué comme retourné et l\'exemplaire est maintenant disponible.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', 'Une erreur est survenue lors du retour: ' . $e->getMessage());
    }
}

public function Returned($id)
{
    // This is essentially the same as markReturned, so we can just call it
    return $this->markReturned($id);
}


    
}