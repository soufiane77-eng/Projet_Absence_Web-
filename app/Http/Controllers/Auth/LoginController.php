<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LoginHistory;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect($this->redirectTo());
        }

        $showCaptcha = session()->get('show_captcha', false);

        return view('auth.login', compact('showCaptcha'));
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $user = User::where('username', $request->username)->first();

        $error = 'Les identifiants fournis ne correspondent pas à nos enregistrements.';

        if (!$user) {
            throw ValidationException::withMessages(['username' => $error]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'username' => 'Votre compte a été désactivé. Contactez l\'administrateur.',
            ]);
        }

        if ($user->isLocked()) {
            $remaining = now()->diffInMinutes($user->locked_until);
            throw ValidationException::withMessages([
                'username' => "Compte verrouillé après trop de tentatives. Réessayez dans {$remaining} minutes.",
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            $user->increment('login_attempts');
            $newAttempts = $user->fresh()->login_attempts;

            if ($newAttempts >= 3) {
                session()->flash('show_captcha', true);
            }

            if ($newAttempts >= 5) {
                $user->update(['locked_until' => now()->addMinutes(30)]);
                ActivityLog::log('user.account.locked', "Compte verrouillé après {$newAttempts} tentatives échouées : {$request->username}", [
                    'ip' => $request->ip(),
                ]);
            }

            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at' => now(),
                'login_successful' => false,
            ]);

            throw ValidationException::withMessages(['username' => $error]);
        }

        $user->update([
            'login_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        session()->forget('show_captcha');

        LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_at' => now(),
            'login_successful' => true,
        ]);

        ActivityLog::log('user.login', "Utilisateur connecté : {$request->username}", [
            'ip' => $request->ip(),
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo());
    }

    public function logout(Request $request): RedirectResponse
    {
        $username = Auth::user()->username ?? Auth::user()->email ?? 'inconnu';

        ActivityLog::log('user.logout', "Utilisateur déconnecté : {$username}");

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function redirectTo(): string
    {
        $user = Auth::user();
        return match ($user->role) {
            'admin' => '/admin/dashboard',
            'teacher' => '/teacher/dashboard',
            'student' => '/student/dashboard',
            default => '/login',
        };
    }
}
