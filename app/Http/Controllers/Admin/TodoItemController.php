<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TodoItem;

class TodoItemController extends Controller
{
    public function index()
    {
        $todoItems = TodoItem::orderBy('created_at', 'desc')->get();
        return view('admin.todolist.index', compact('todoItems'));
    }

    public function create()
    {
        return view('admin.todolist.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed',
            'progress' => 'required|integer|min:0|max:100',
            'color' => 'required|string|in:primary,success,info,warning,danger',
            'expected_date' => 'nullable|date',
        ]);
        
        TodoItem::create($validated);
        
        return redirect()->route('admin.todolist')->with('success', 'Fonctionnalité ajoutée avec succès.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(TodoItem $todoItem)
    {
        return view('admin.todolist.edit', compact('todoItem'));
    }

    public function update(Request $request, TodoItem $todoItem)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed',
            'progress' => 'required|integer|min:0|max:100',
            'color' => 'required|string|in:primary,success,info,warning,danger',
            'expected_date' => 'nullable|date',
        ]);
        
        $todoItem->update($validated);
        
        return redirect()->route('admin.todolist')->with('success', 'Fonctionnalité mise à jour avec succès.');
    }

    public function destroy(TodoItem $todoItem)
    {
        $todoItem->delete();
        return redirect()->route('admin.todolist')->with('success', 'Fonctionnalité supprimée avec succès.');
    }
}
