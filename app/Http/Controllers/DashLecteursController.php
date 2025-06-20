<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Pret;
use App\Models\Notice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashLecteursController extends Controller
{

    
    public function index()
{
    $lecteur = Auth::user(); // ou Auth::guard('lecteur')->user(); selon ton guard

    $prets = Pret::with('notice')
        ->where('lecteur_id', $lecteur->lec_id)
        ->get();

    return view('lecteurs.dashboard', compact('prets'));
}
public function suggest(Request $request)
{
    $term = $request->input('term');

    $results = DB::table('prets')
        ->join('lecteurs', 'prets.lec_id', '=', 'lecteurs.lec_id')
        ->join('documents', 'prets.doc_id', '=', 'documents.doc_id')
        ->where(function ($q) use ($term) {
            $q->where('prets.pret_id', 'like', '%' . $term . '%')
              ->orWhere('lecteurs.lec_nom', 'like', '%' . $term . '%')
              ->orWhere('lecteurs.lec_prenom', 'like', '%' . $term . '%')
              ->orWhere('documents.doc_titre', 'like', '%' . $term . '%');
        })
        ->select(
            'prets.pret_id',
            'lecteurs.lec_nom',
            'lecteurs.lec_prenom',
            'documents.doc_titre'
        )
        ->limit(7)
        ->get();

    $formatted = $results->map(function ($item) {
        return [
            'label' => "P-{$item->pret_id} - {$item->lec_nom} {$item->lec_prenom} ({$item->doc_titre})",
            'value' => "P-{$item->pret_id}",
        ];
    });

    return response()->json($formatted);
}

    public function profile()
    {
        $lecteur = Auth::user();
        return view('lecteurs.profile', compact('lecteur'));
    }

    public function history()
    {
        $prets = Pret::where('lecteur_id', Auth::id())->latest()->get();
        return view('lecteurs.history', compact('prets'));
    }


    public function prolongation($pret_id)
    {
        $pret = Pret::where('id', $pret_id)->where('lecteur_id', Auth::id())->firstOrFail();
        $pret->date_retour = now()->addDays(14);
        $pret->save();
        return back()->with('success', 'Prolongation réussie.');
    }

    public function retour($pret_id)
    {
        $pret = Pret::where('id', $pret_id)->where('lecteur_id', Auth::id())->firstOrFail();
        $pret->returned = true;
        $pret->save();
        return back()->with('success', 'Retour effectué.');
    }
    public function search(Request $request)
{
    $query = $request->input('q');
    $notices = Notice::where('doc_titre', 'like', "%{$query}%")
                     ->orWhere('doc_auteur', 'like', "%{$query}%")
                     ->get();

    return view('lecteurs.search_results', compact('notices',));
}


public function category(Request $request)
{
    $cat_id = $request->query('cat_id');

    // Récupère tous les livres/notice de cette catégorie
    $livres = Notice::where('categorie_id', $cat_id)->get();

    return view('lecteurs.category', compact('livres'));
}
public function show(Request $request)
{
    $category = Category::where('cat_id', $request->cat_id)->first();

    if (!$category) {
        abort(404, 'Category not found');
    }

    return view('lecteurs.category', compact('category'));
}

}
