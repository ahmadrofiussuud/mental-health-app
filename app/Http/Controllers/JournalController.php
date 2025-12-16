<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Journal;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function index()
    {
        $journals = Journal::where('user_id', Auth::id())
            ->latest()
            ->get();
            
        return view('journal.index', compact('journals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'mood' => 'required|string'
        ]);

        Journal::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'mood' => $request->mood,
            'is_anonymous' => $request->has('is_anonymous'),
        ]);

        return redirect()->route('journal.index')->with('success', 'Journal entry saved successfully!');
    }

    public function destroy($id)
    {
        $journal = Journal::where('user_id', Auth::id())->findOrFail($id);
        $journal->delete();

        return redirect()->route('journal.index')->with('success', 'Entry deleted.');
    }
}
