<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PretEmployer;
use App\Models\noticeExemplaire;

class PretEmployerController extends Controller
{
    // Show form to create a new pret employer
    public function create()
    {
        $exemplaires = noticeExemplaire::where('etat', 'disponible')->get();
        return view('dashboard.create', compact('exemplaires'));
    }

    // Store the submitted data
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:191',
            'prenom' => 'required|string|max:191',
            'doc_titre' => 'required|string|max:191',
            'exp_id' => 'required|exists:notice_exemplaires,exp_id',
            'post' => 'required|string|max:191',
            'num_tel' => 'nullable|string|max:191',
        ]);

        // Create the loan
        $pret = PretEmployer::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'doc_titre' => $request->doc_titre,
            'exp_id' => $request->exp_id,
            'post' => $request->post,
            'num_tel' => $request->num_tel,
            'statut' => 'emprunte' // Set initial status
        ]);

        // Update exemplaire status to 'emprunté'
        noticeExemplaire::where('exp_id', $request->exp_id)
            ->update(['etat' => 'emprunte']);

        return redirect()->route('dashboard.create')->with('success', 'Prêt ajouté avec succès !');
    }

    // Optional: List all pret employers
    public function show()
    {
        $prets = PretEmployer::latest()->paginate(10);
        return view('dashboard.pretemployers', compact('prets'));
    }
    
    public function returnPret($id)
    {
        $pret = PretEmployer::findOrFail($id);

        if ($pret->statut !== 'retourneé') {
            $pret->statut = 'retourneé';
            $pret->save();

            // Update exemplaire status to 'disponible'
            noticeExemplaire::where('exp_id', $pret->exp_id)
                ->update(['etat' => 'disponible']);
        }

        // Création dans la table "returneds"
        \App\Models\Returned::create([
            'is_admin' => auth()->check() ? auth()->user()->is_admin : 0,
            'doc_titre' => $pret->doc_titre ?? 'Inconnu',
        ]);

        return redirect()->back()->with('success', 'Le prêt a été marqué comme retourné.');
    }
}