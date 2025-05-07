<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class LoginAsController extends Controller
{
    /**
     * Melakukan login sebagai user lain (impersonation)
     */
    public function loginAs(User $user)
    {
        // Cek apakah user adalah super_admin
        if (!auth()->user()->hasRole('super_admin')) {
            Notification::make()
                ->title('Anda tidak memiliki izin untuk melakukan tindakan ini')
                ->danger()
                ->send();

            return redirect()->back();
        }

        // Pastikan tidak login as ke diri sendiri
        if (auth()->id() === $user->id) {
            Notification::make()
                ->title('Anda tidak dapat login sebagai diri sendiri')
                ->danger()
                ->send();

            return redirect()->back();
        }

        // Pastikan pengguna aktif
        if (!$user->is_active) {
            Notification::make()
                ->title('Pengguna tidak aktif')
                ->danger()
                ->send();

            return redirect()->back();
        }

        // Simpan ID admin saat ini di session
        $adminId = auth()->id();
        session(['admin_id' => $adminId]);
        session(['login_as' => true]);

        // Logout user saat ini dan login sebagai user yang dituju
        Auth::logout();

        // Regenerate session ID untuk mencegah session fixation
        session()->invalidate();
        session()->regenerate();

        // Set ulang informasi login as setelah regenerate session
        session(['admin_id' => $adminId]);
        session(['login_as' => true]);

        // Login sebagai user yang dipilih
        Auth::login($user);

        // Catat log
        Log::info('User dengan ID ' . $adminId . ' melakukan login as sebagai user ID ' . $user->id);

        // Redirect ke dashboard sesuai role
        if ($user->hasRole('mahasiswa')) {
            $path = route('filament.mahasiswa.pages.dashboard');
        } elseif ($user->hasRole('dosen')) {
            $path = route('filament.dosen.pages.dashboard');
        } else {
            $path = route('filament.admin.pages.dashboard');
        }

        Notification::make()
            ->title('Berhasil login sebagai ' . $user->name)
            ->success()
            ->send();

        return redirect($path);
    }

    /**
     * Kembali ke akun admin asli
     */
    public function returnToAdmin()
    {
        if (session()->has('admin_id') && session()->has('login_as')) {
            $adminId = session('admin_id');
            $adminUser = User::find($adminId);

            if ($adminUser) {
                // Logout dari user saat ini
                Auth::logout();

                // Regenerate session ID untuk mencegah session fixation
                session()->invalidate();
                session()->regenerate();

                // Login sebagai admin
                Auth::login($adminUser);

                // Hapus sesi login as
                session()->forget('admin_id');
                session()->forget('login_as');
                session()->forget('login_as_notified');

                Notification::make()
                    ->title('Kembali ke akun admin')
                    ->success()
                    ->send();

                return redirect()->route('filament.admin.pages.dashboard');
            }
        }

        return redirect('/');
    }
}
