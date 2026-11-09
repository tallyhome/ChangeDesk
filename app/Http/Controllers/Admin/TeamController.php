<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index()
    {
        $members = User::query()
            ->where('tenant_id', Tenant::current()->id)
            ->where('role', User::ROLE_CLIENT)
            ->orderBy('name')
            ->get();

        return view('admin.team.index', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => $validated['password'],
            'role' => User::ROLE_CLIENT,
            'project_role' => User::PROJECT_EDITOR,
            'tenant_id' => Tenant::current()->id,
            'is_active' => true,
        ]);

        return redirect()->route('admin.team.index')->with('success', __('app.admin.team_created'));
    }

    public function reset(User $user)
    {
        $this->assertEditor($user);
        $temp = Str::password(12);
        $user->password = $temp;
        $user->save();

        return redirect()->route('admin.team.index')->with('temp_password', $temp);
    }

    public function destroy(User $user)
    {
        $this->assertEditor($user);
        abort_if($user->id === auth()->id(), 403);
        $user->delete();

        return redirect()->route('admin.team.index')->with('success', __('app.admin.team_removed'));
    }

    private function assertEditor(User $user): void
    {
        abort_unless($user->tenant_id === Tenant::current()?->id, 404);
        abort_unless($user->isEditor(), 403);
    }
}
