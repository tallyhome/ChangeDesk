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
        // Récupérer les bugs récemment signalés (limités à 5 par exemple)
        $recentBugs = \App\Models\BugReport::orderBy('created_at', 'desc')->take(5)->get();
        
        return view('pages.bug-report', compact('recentBugs'));
    }
    
    // Dans la méthode storeBugReport
    
    public function storeBugReport(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'captcha' => 'required|in:5', // Simple captcha validation
        ]);
        
        // Créer un nouveau rapport de bug
        $bugReport = new \App\Models\BugReport();
        $bugReport->title = $validated['title'];
        $bugReport->description = $validated['description'];
        $bugReport->status = 'open'; // Par défaut, le statut est "ouvert"
        $bugReport->progress = 0; // Par défaut, la progression est à 0%
        $bugReport->color = 'danger'; // Par défaut, la couleur est rouge (danger)
        $bugReport->severity = 'medium'; // Par défaut, la sévérité est moyenne
        
        // Enregistrer les informations de contact si fournies
        if (!empty($validated['name'])) {
            $bugReport->reporter_name = $validated['name'];
        }
        
        if (!empty($validated['email'])) {
            $bugReport->reporter_email = $validated['email'];
        }
        
        $bugReport->save();
        
        return redirect()->route('bug-report')->with('success', 'Votre signalement de bug a été enregistré avec succès. Merci de votre contribution !');
    }
    
    // Nouvelle méthode pour afficher les détails d'un bug
    public function showBugReport($id)
    {
        $bug = \App\Models\BugReport::findOrFail($id);
        return view('pages.bug-report-detail', compact('bug'));
    }
}