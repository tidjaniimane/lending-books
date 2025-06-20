<?php

namespace App\Http\Controllers;

use App\Models\DemandePret;
use App\Models\Notice;
use App\Models\Lecteur;
use Illuminate\Http\Request;
use App\Models\Pret;
use App\Models\NoticeExemplaire;

class DemandeController extends Controller
{
    /**
     * Afficher toutes les demandes de prêt.
     */
    public function index(Request $request)
{
     $query = DemandePret::with(['lecteur', 'notice'])
        ->where('statu', 'pret'); 

    if ($request->filled('search')) {
        $search = $request->input('search');

        $query->where(function($q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
              ->orWhereHas('lecteur', function($q) use ($search) {
                  $q->where('lec_nom', 'like', "%{$search}%");
              })
              ->orWhereHas('noticeExemplaire.notice', function($q) use ($search) {
                  $q->where('doc_titre', 'like', "%{$search}%");
              })
              ->orWhere('statut', 'like', "%{$search}%");
        });
    }

    $demandes = $query->orderBy('date_demande', 'desc')->paginate(10);

    return view('dashboard.requests', compact('demandes'));
}


    /**
     * Mettre à jour le statut d'une demande.
     */
    public function updateStatut(Request $request, $id)
{
    $request->validate([
        'statut' => 'required|in:Acceptée,Refusée',
    ]);

    $demande = DemandePret::findOrFail($id);
    $lecteur = $demande->lecteur;
    $exemplaire = NoticeExemplaire::find($demande->exp_id);

    if ($request->statut === 'Acceptée') {
        // 1. Trouver l'exemplaire original demandé
        $originalExemplaire = NoticeExemplaire::find($demande->exp_id);

        if (!$originalExemplaire) {
            return redirect()->route('requests')->with('error', 'Exemplaire original introuvable.');
        }

        // 2. Trouver un autre exemplaire disponible du même document
        $availableExemplaire = NoticeExemplaire::where('doc_id', $originalExemplaire->doc_id)
            ->where('etat', 'disponible')
            ->first();
            

        if (!$availableExemplaire) {
            return redirect()->route('requests')->with('error', 'Aucun exemplaire disponible pour ce document.');
        }

        // 3. Créer le prêt
        Pret::create([
            'lec_id' => $demande->lec_id,
            'doc_id' => $demande->doc_id,
            'exp_id' => $exemplaire->exp_id,
            'date_pret' => now(),
            'date_retour' => now()->addDays(14),
            'statut' => 'en_cours',
        ]);

        // 4. Marquer l'exemplaire comme emprunté
        $availableExemplaire->etat = 'emprunte';
        $availableExemplaire->save();

        // 5. Supprimer la demande
        $demande->delete();

        \Log::info("Notification envoyée au lecteur ID {$lecteur->id}: Demande acceptée.");
        return redirect()->route('requests')->with('success', 'Demande acceptée et prêt enregistré.');
    } else {
        // Refusée : juste supprimer la demande
        $demande->delete();

        \Log::info("Notification envoyée au lecteur ID {$lecteur->id}: Demande refusée.");
        return redirect()->route('requests')->with('success', 'Demande refusée et supprimée.');
    }
}


    /**
     * Afficher le formulaire de création de demande.
     */
    public function create()
    {
        $notices = Notice::orderBy('doc_titre')->get();
        return view('demande.create', compact('notices'));
    }

    /**
     * Enregistrer une nouvelle demande de prêt.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lec_id' => 'required|exists:lecteurs,id',
            'doc_id' => 'required|exists:notices,id',
        ]);

        $demande = DemandePret::create([
            'lec_id' => $request->lec_id,
            'doc_id' => $request->doc_id,
            'date_demande' => now(),
            'statut' => 'En attente',
        ]);

        return redirect()->route('requests')->with('success', 'Demande de prêt créée avec succès');
    }



}
