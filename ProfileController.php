<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('home');  // Assurez-vous que 'home' est le bon nom de la vue
    }
    // Afficher le profil utilisateur
    public function show()
    {
        $user = Auth::user();  // Récupère l'utilisateur connecté
        return view('profile.index', compact('user'));  // Passe la variable à la vue
    }

    // Afficher le formulaire pour modifier le profil
    public function edit()
    {
        $user = Auth::user();  // Récupère l'utilisateur connecté
        return view('profile.edit', compact('user'));  // Passe la variable à la vue
    }

    // Mettre à jour le profil utilisateur
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $user->update($request->only('name', 'email'));

        // Mise à jour de l'image de profil
        if ($request->hasFile('image')) {
            // Supprimer les anciennes images de profil
            $user->clearMediaCollection('profile_images');
            $user->addMediaFromRequest('image')->toMediaCollection('profile_images');
        }

        return redirect()->route('profile')->with('success', 'Profil mis à jour avec succès.');
    }

    // Afficher le formulaire pour changer le mot de passe
    public function showChangePasswordForm()
    {
        return view('auth.passwords.change');
    }

    // Changer le mot de passe
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'L\'ancien mot de passe est incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile')->with('success', 'Mot de passe changé avec succès.');
    }
}
