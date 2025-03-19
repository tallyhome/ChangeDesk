<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Version;
use App\Models\TodoItem;
use App\Models\BugReport;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'home')->first();
        return view('pages.home', compact('page'));
    }

    public function terms()
    {
        $page = Page::where('slug', 'terms')->first();
        return view('pages.terms', compact('page'));
    }

    public function privacy()
    {
        $page = Page::where('slug', 'privacy')->first();
        return view('pages.privacy', compact('page'));
    }

    public function changelog()
    {
        $versions = Version::orderBy('release_date', 'desc')->get();
        return view('pages.changelog', compact('versions'));
    }
    
    public function todolist()
    {
        $todoItems = TodoItem::orderBy('priority', 'desc')->get();
        return view('pages.todolist', compact('todoItems'));
    }
    
    public function bugReport()
    {
        $bugReports = BugReport::orderBy('created_at', 'desc')->get();
        return view('pages.bug-report', compact('bugReports'));
    }
    
    public function storeBugReport(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'reporter_name' => 'required|string|max:255',
            'reporter_email' => 'required|email',
            'human_check' => 'required|in:5',
            'website' => 'prohibited|nullable' // Champ honeypot
        ], [
            'human_check.in' => 'La réponse à la question anti-robot est incorrecte.'
        ]);
        
        BugReport::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'reporter_name' => $validated['reporter_name'],
            'reporter_email' => $validated['reporter_email'],
            'status' => 'new'
        ]);
        
        return redirect()->route('bug-report')->with('success', 'Votre rapport de bug a été soumis avec succès.');
    }
}