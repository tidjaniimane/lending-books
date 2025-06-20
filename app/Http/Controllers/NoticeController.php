<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::all();
        return view('notices.index', compact('notices'));
    }

    public function create()
    {
        return view('notices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        Notice::create($request->all());
        return redirect()->route('notices.index');
    }

    public function show(Notice $notice)
    {
        return view('notices.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
        return view('notices.edit', compact('notice'));
    }

    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $notice->update($request->all());
        return redirect()->route('notices.index');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        return redirect()->route('notices.index');
    }
    

    
public function search(Request $request)
{
    $query = $request->input('q');

    $results = Notice::where('doc_titre', 'like', "%{$query}%")
        ->orWhere('doc_auteur', 'like', "%{$query}%")
        ->get();

    return response()->json($results);
}
    
}
