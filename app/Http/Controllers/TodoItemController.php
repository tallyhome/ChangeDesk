public function index()
{
    $todoItems = TodoItem::orderBy('created_at', 'desc')->get();
    return view('pages.todolist', compact('todoItems'));
}