<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandePret;
use App\Models\Lecteur;
use App\Models\Pret;
use App\Models\NoticeExemplaire;

class DemandepretController extends Controller
{
    /**
     * Enregistre une nouvelle demande de prêt.
     */
    public function store(Request $request)
{
    $request->validate([
        'lec_id' => 'required|exists:lecteurs,lec_id',
        'doc_id' => 'required|exists:notices,doc_id',
        'exp_id' => 'required|exists:notice_exemplaires,exp_id',
    ]);

    DemandePret::create([
        'lec_id' => $request->lec_id,
        'exp_id' => $request->exp_id,
        'statut' => 'en attente',
        'statu' =>  'pret',
        'date_demande' => now(),
    ]);

    return redirect()->back()->with('success', 'Demande de prêt envoyée avec succès.');
}
    
    /**
     * Enregistre une demande de renouvellement de prêt.
     */
    public function renouveler(Request $request)
    {
        // Validate the request
        $request->validate([
            'pret_id' => 'required|exists:prets,pret_id'
        ]);
        
        // Find the loan with detailed logging
        $pret = Pret::findOrFail($request->pret_id);
        
        // Debug info to see the loan details
        \Log::info('Pret found: ', ['pret' => $pret->toArray()]);
        
        // The error suggests exp_id is missing - let's explicitly get the related NoticeExemplaire
        $NoticeExemplaire = NoticeExemplaire::where('exp_id', $pret->exp_id)->first();
        
        // If no NoticeExemplaire found, try getting from the relationship
        if (!$NoticeExemplaire && method_exists($pret, 'NoticeExemplaire')) {
            $NoticeExemplaire = $pret->NoticeExemplaire;
        }
        
        // Final fallback - if no NoticeExemplaire relation, we need to debug further
        if (!$NoticeExemplaire) {
            \Log::error('No NoticeExemplaire found for pret: ' . $request->pret_id);
            return redirect()->back()->with('error', 'Impossible de trouver l\'NoticeExemplaire associé à ce prêt.');
        }
        
        \Log::info('NoticeExemplaire found: ', ['NoticeExemplaire' => $NoticeExemplaire->toArray()]);
        
        // Get the reader ID - either from authentication or from the loan
        $lec_id = auth()->check() ? auth()->id() : $pret->lec_id;
        
        // Create a new renewal request with explicit values
        $demande = new DemandePret();
        $demande->lec_id = $lec_id;
        $demande->exp_id = $NoticeExemplaire->exp_id; // Make sure this is set
        $demande->date_demande = now();
        $demande->statut = 'En attente';
        $demande->statu = 'renouveler';
        $demande->save();
        
        \Log::info('DemandePret created: ', ['demande' => $demande->toArray()]);
        
        return redirect()->back()->with('success', 'Demande de renouvellement envoyée.');
    }
}