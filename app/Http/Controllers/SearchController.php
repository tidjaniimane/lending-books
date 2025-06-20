<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;
use App\Models\Recherche;
use Illuminate\Support\Facades\DB;
use App\Models\Categorie;
use App\Models\NoticeExemplaire;
use App\Models\Pret;
use App\Models\Lecteur;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function search(Request $request)
{
    $query = $request->input('query');

    // 💾 Log the search into 'recherches' table
    DB::table('recherches')->insert([
        'query' => $query,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // 🔍 Perform your actual search (example: search in notices)
     $notices = Notice::where('doc_titre', 'like', "%{$query}%")
                     ->orWhere('doc_auteur', 'like', "%{$query}%")
                     ->get();

    // Return results to view
    return view('lecteurs.search_results', compact('notices'));
}
}
