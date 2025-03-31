<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BugReport;

class BugReportController extends Controller
{
    public function index()
    {
        $bugReports = BugReport::orderBy('created_at', 'desc')->get();
        return view('admin.bug_reports.index', compact('bugReports'));
    }

    public function create()
    {
        return view('admin.bug_reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|string|in:low,medium,high,critical',
            'status' => 'required|string|in:open,in_progress,resolved,closed',
            'progress' => 'required|integer|min:0|max:100',
            'color' => 'required|string|in:primary,success,info,warning,danger',
            'expected_fix_date' => 'nullable|date',
        ]);
        
        $bugReport = new \App\Models\BugReport();
        $bugReport->title = $validated['title'];
        $bugReport->description = $validated['description'];
        $bugReport->severity = $validated['severity'];
        $bugReport->status = $validated['status'];
        $bugReport->progress = $validated['progress'];
        $bugReport->color = $validated['color'];
        
        // Définir une valeur par défaut pour reporter_name et reporter_email
        $bugReport->reporter_name = 'Admin';
        $bugReport->reporter_email = 'admin@example.com'; // Ajout de cette ligne
        
        if ($request->has('expected_fix_date') && $request->expected_fix_date) {
            $bugReport->expected_fix_date = $request->expected_fix_date;
        }
        
        $bugReport->save();
        
        return redirect()->route('admin.bug_reports')->with('success', 'Rapport de bug créé avec succès.');
    }

    public function edit(BugReport $bugReport)
    {
        return view('admin.bug_reports.edit', compact('bugReport'));
    }

    public function update(Request $request, BugReport $bugReport)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'progress' => 'required|integer|min:0|max:100',
            'color' => 'required|string|in:primary,success,info,warning,danger',
            'severity' => 'required|in:low,medium,high,critical',
            'expected_fix_date' => 'nullable|date',
        ]);
        
        $bugReport->update($validated);
        
        return redirect()->route('admin.bug_reports')->with('success', 'Rapport de bug mis à jour avec succès.');
    }

    public function destroy(BugReport $bugReport)
    {
        $bugReport->delete();
        
        return redirect()->route('admin.bug_reports')->with('success', 'Rapport de bug supprimé avec succès.');
    }

    public function toggleBugReportStatus()
    {
        $setting = \App\Models\Setting::where('key', 'bug_report_enabled')->first();
        
        if (!$setting) {
            $setting = new \App\Models\Setting();
            $setting->key = 'bug_report_enabled';
            $setting->value = '1';
        }
        
        $setting->value = $setting->value === '1' ? '0' : '1';
        $setting->save();
        
        return response()->json([
            'success' => true,
            'value' => $setting->value,
            'message' => $setting->value === '1' ? 'Système de rapports de bugs activé' : 'Système de rapports de bugs désactivé'
        ]);
    }
}
