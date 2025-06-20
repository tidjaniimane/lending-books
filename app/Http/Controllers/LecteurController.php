<?php

namespace App\Http\Controllers;

use App\Models\Lecteur;
use Illuminate\Http\Request;
use App\Models\Pret;
use App\Models\Notice;
use App\Models\Categorie;

class LecteurController extends Controller
{
    
   public function dashboard()
{
    $lecteur = auth()->user();

       $prets = Pret::where('lec_id', $lecteur->lec_id)->where('statut', 'en_cours')->get();
    $categories = Categorie::all();

    

    return view('lecteurs.dashboard', compact('prets','categories',));
}
    public function index(Request $request)
{
    $query = Lecteur::where('is_admin', 0); 
    

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;

        $query->where(function($q) use ($search) {
            $q->where('lec_nom', 'like', "%{$search}%")
              ->orWhere('lec_email', 'like', "%{$search}%")
              ->orWhere('lec_id', 'like', "%{$search}%");
        });
    }

    $lecteurs = $query->orderBy('lec_nom')->paginate(10);

    return view('lecteurs.index', compact('lecteurs'));
}


    // Show the form to create a new lecteur
    public function create()
    {
        return view('lecteurs.create');
    }

    // Store a new lecteur
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:lecteurs,email',
            'telephone' => 'nullable|string|max:20',
        ]);

        Lecteur::create($request->all());

        return redirect()->route('lecteurs.index')->with('success', 'Lecteur ajouté avec succès.');
    }

    // Show the form to edit an existing lecteur
  
    // Update an existing lecteur
    public function update(Request $request, $id)
{
    $lecteur = Lecteur::findOrFail($id);

    $request->validate([
        'lec_nom' => 'required|string|max:255',
        'lec_prenom' => 'required|string|max:255',
        'lec_email' => 'required|email|unique:lecteurs,lec_email,' . $lecteur->lec_id . ',lec_id',
    ]);

    $lecteur->lec_nom = $request->lec_nom;
    $lecteur->lec_prenom = $request->lec_prenom;
    $lecteur->lec_email = $request->lec_email;
    $lecteur->save();

    return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
}


    // Delete a lecteur
    public function destroy(Lecteur $lecteur)
    {
        $lecteur->delete();

        return redirect()->route('lecteurs.index')->with('success', 'Lecteur supprimé avec succès.');
    }
    public function pret()
{
    // récupère les prêts du lecteur connecté
    $lecteurId = auth()->user()->lec_id;
    $prets = Pret::where('lec_id', $lecteurId)->get();

    return view('lecteurs.pret', compact('prets'));
}

public function show($id)
{
    // example logic
    $lecteur = Lecteur::findOrFail($id);
    return view('lecteurs.show', compact('lecteur'));
}
public function profile()
{
    $lecteur = auth()->user(); // get the logged-in user
    return view('lecteurs.profile', compact('lecteur')); // make sure this Blade file exists
}
public function history()
{
    $lecteur = auth()->user();

    // Charger les prêts avec leurs notices associées
    $prets = Pret::where('lec_id', $lecteur->lec_id)->with('notice')->get();

    return view('lecteurs.history', compact('prets'));
}

public function retourner($id)
{
    $pret = Pret::findOrFail($id);

    if (!$pret->returned) {
        $pret->returned = true;
        $pret->date_retour = now(); // ou la date actuelle si tu la gères
        $pret->save();
    }

    return redirect()->route('lecteur.history')->with('success', 'Livre retourné avec succès.');
}
public function prolonger($id)
{
    $pret = Pret::findOrFail($id);

    if (!$pret->returned) {
        $pret->date_retour = \Carbon\Carbon::parse($pret->date_retour)->addWeeks(1);
        $pret->save();
    }

    return redirect()->route('lecteur.history')->with('success', 'Date de retour prolongée.');
}
public function editProfile()
{
    $lecteur = auth()->user();
    return view('lecteurs.edit', compact('lecteur'));
}
public function showNotices(Request $request)
{
    $lecteurId = auth()->user()->id;

    // Rechercher les notices
    $search = $request->input('q');
    $notices = Notice::query();
    if ($search) {
        $notices->where('doc_titre', 'like', "%{$search}%");
    }

    // Obtenir les prêts en cours du lecteur connecté
    $pretsEnCours = Pret::with('notice')
        ->where('lec_id', $lecteurId)
        ->where('statut', 'en_cours')
        ->get();

    return view('lecteur.notices', [
        'notices' => $notices->get(),
        'prets' => $pretsEnCours,
    ]);
}

public function showCategory($categoryId)
{
    // Get the main category
    $category = Categorie::findOrFail($categoryId);

    // Get subcategory IDs for this category
    $subCategoryIds = Categorie::where('parent_id', $categoryId)->pluck('cat_id');

    // Get all notices (books) where category_id is in the subcategories
    $notices = Notice::whereIn('cat_id', $subCategoryIds)->get();

    return view('lecteurs.category', compact('category', 'notices'));
}




}
