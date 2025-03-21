<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminProfileController extends Controller
{
    /**
     * Affiche le formulaire de modification du profil administrateur
     */
    public function edit()
    {        
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Met à jour le profil administrateur
     */
    public function update(Request $request)
    {        
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Vérifier le mot de passe actuel si un nouveau mot de passe est fourni
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Le mot de passe actuel est incorrect.',
                ]);
            }
        }
        
        // Mettre à jour les informations de base
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        // Mettre à jour le mot de passe si fourni
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profil mis à jour avec succès');
    }
}