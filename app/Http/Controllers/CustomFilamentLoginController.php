<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class CustomFilamentLoginController extends Controller
{
    public function showLoginForm()
    {
        // Get quick login users for dev environment
        $quickLoginUsers = [];

        if (app()->environment('local')) {
            $quickLoginUsers = User::where('is_active', true)
                ->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['super_admin', 'admin', 'dosen', 'mahasiswa']);
                })
                ->select('id', 'name', 'email')
                ->take(5)
                ->get();
        }

        return view('auth.filament-login', [
            'quickLoginUsers' => $quickLoginUsers
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect user based on their role
            if ($user->hasAnyRole(['super_admin', 'admin'])) {
                return redirect()->route('filament.admin.pages.dashboard');
            } elseif ($user->hasRole('dosen')) {
                return redirect()->route('filament.dosen.pages.dashboard');
            } elseif ($user->hasRole('mahasiswa')) {
                return redirect()->route('filament.mahasiswa.pages.dashboard');
            }

            // Fallback to admin panel if no specific role match
            return redirect()->route('filament.admin.pages.dashboard');
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('filament.custom.login');
    }

    // Quick login for dev environment
    public function quickLogin(Request $request, $userId)
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $user = User::findOrFail($userId);
        Auth::login($user);
        $request->session()->regenerate();

        // Redirect user based on their role
        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return redirect()->route('filament.admin.pages.dashboard');
        } elseif ($user->hasRole('dosen')) {
            return redirect()->route('filament.dosen.pages.dashboard');
        } elseif ($user->hasRole('mahasiswa')) {
            return redirect()->route('filament.mahasiswa.pages.dashboard');
        }

        // Fallback to admin panel if no specific role match
        return redirect()->route('filament.admin.pages.dashboard');
    }
}
