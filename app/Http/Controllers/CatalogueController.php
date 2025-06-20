<?php

namespace App\Http\Controllers;

use App\Models\categorie;

use App\Models\Notice;
use Illuminate\Http\Request;


class CatalogueController extends Controller
{
    // Show all parent categories
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Categorie::whereNull('parent_id')
            ->when($search, function ($query, $search) {
                $keywords = explode(' ', $search);

                $query->where(function($q) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $q->orWhere('nom', 'like', '%' . $keyword . '%');
                    }
                });
            })
            ->get();

        return view('dashboard.catalogue.index', compact('categories', 'search'));
    }

    // Autocomplete suggestions endpoint for AJAX
    public function suggestions(Request $request)
{
    $term = $request->input('term');

    $suggestions = [];

    if ($term) {
        $suggestions = Categorie::where('nom', 'like', '%' . $term . '%')
            ->limit(10)
            ->pluck('nom')
            ->toArray();
    }

    return response()->json($suggestions);
}


public function showSubcategories($cat_id)
{
    $subcategories = categorie::where('parent_id', $cat_id)->get();
    $parentCategory = categorie::findOrFail($cat_id);

    return view('dashboard.catalogue.subcategories', compact('subcategories', 'parentCategory'));
}

public function showNotices($cat_id)
{
    $notices = Notice::where('cat_id', $cat_id)->get(); // assuming 'indice' is the category ID in notices table
    $subcategory = categorie::findOrFail($cat_id);

    return view('dashboard.catalogue.notices', compact('notices', 'subcategory'));
}

}
