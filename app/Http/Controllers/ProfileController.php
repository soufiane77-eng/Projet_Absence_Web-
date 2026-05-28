<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function showProfile(): View
    {
        $user = Auth::user()->load(['student', 'teacher']);
        return view('profile.show', compact('user'));
    }

    public function showSettings(): View
    {
        // Les paramètres sont intégrés dans la page profil
        return $this->showProfile();
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        ActivityLog::log('profile.updated', "Profil mis à jour par {$user->name}");

        return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|max:100|confirmed',
        ], [
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        ActivityLog::log('password.changed', "Mot de passe changé par {$user->name}");

        return redirect()->back()->with('success', 'Mot de passe changé avec succès.');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);
        }

        ActivityLog::log('avatar.updated', "Avatar mis à jour par {$user->name}");

        return redirect()->back()->with('success', 'Avatar mis à jour avec succès.');
    }
}
