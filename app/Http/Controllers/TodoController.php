// ... code existant ...

public function destroy($id)
{
    try {
        $todo = Todo::findOrFail($id);
        $todo->delete();
        
        return redirect()->route('todos.index')->with('success', 'Tâche supprimée avec succès');
    } catch (\Exception $e) {
        // Log l'erreur pour le débogage
        \Log::error('Erreur lors de la suppression de la tâche: ' . $e->getMessage());
        
        return redirect()->route('todos.index')->with('error', 'Erreur lors de la suppression de la tâche');
    }
}

// ... code existant ...