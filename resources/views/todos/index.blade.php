<!-- ... code existant ... -->

<form action="{{ route('todos.destroy', $todo->id) }}" method="POST" style="display: inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche?')">
        Supprimer
    </button>
</form>

<!-- ... code existant ... -->