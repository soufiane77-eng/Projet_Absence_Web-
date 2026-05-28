<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $user->assignRole($request->role);

        ActivityLog::log('user.created', "User created: {$request->email} (Role: {$request->role})", [
            'created_by' => auth()->id(),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', "User {$request->name} created successfully as {$request->role}.");
    }
}
