<?php

namespace App\Http\Controllers;

use App\Models\NoticeExemplaire;
use Illuminate\Http\Request;

class NoticeExemplaireController extends Controller
{
    public function index()
    {
        $exemplaires = NoticeExemplaire::all();
        return view('notice_exemplaires.index', compact('exemplaires'));
    }

    public function create()
    {
        return view('notice_exemplaires.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'notice_id' => 'required|exists:notices,id',
            'available' => 'required|boolean',
        ]);

        NoticeExemplaire::create($request->all());
        return redirect()->route('notice_exemplaires.index');
    }

    public function show(NoticeExemplaire $noticeExemplaire)
    {
        return view('notice_exemplaires.show', compact('noticeExemplaire'));
    }

    public function edit(NoticeExemplaire $noticeExemplaire)
    {
        return view('notice_exemplaires.edit', compact('noticeExemplaire'));
    }

    public function update(Request $request, NoticeExemplaire $noticeExemplaire)
    {
        $request->validate([
            'notice_id' => 'required|exists:notices,id',
            'available' => 'required|boolean',
        ]);

        $noticeExemplaire->update($request->all());
        return redirect()->route('notice_exemplaires.index');
    }

    public function destroy(NoticeExemplaire $noticeExemplaire)
    {
        $noticeExemplaire->delete();
        return redirect()->route('notice_exemplaires.index');
    }
    public function search(Request $request)
{
    $query = $request->query('q');

    $books = \App\Models\Notice::where('doc_titre', 'like', "%{$query}%")
        ->orWhere('doc_auteur', 'like', "%{$query}%")
        ->limit(10)
        ->get(['exp_id', 'doc_titre', 'doc_auteur']);

    return response()->json($books);
}

}
