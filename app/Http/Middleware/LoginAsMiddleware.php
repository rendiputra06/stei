<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginAsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user sedang dalam mode "login as"
        if (session()->has('admin_id') && session()->has('login_as')) {
            // Tambahkan informasi untuk view
            $adminId = session('admin_id');
            $adminUser = User::find($adminId);

            if ($adminUser && $request->is('admin*') || $request->is('dosen*') || $request->is('mahasiswa*')) {
                // Hanya tampilkan notifikasi di panel Filament

                // Secara opsional, bisa mendaftarkan sesi ke dalam view global
                // view()->share('admin_user', $adminUser);
                // view()->share('is_login_as', true);

                // Tambahkan notifikasi hanya sekali per sesi
                if (!session()->has('login_as_notified')) {
                    Notification::make()
                        ->title('Anda login sebagai ' . Auth::user()->name)
                        ->body('Anda masuk sebagai pengguna lain. Klik "Kembali ke Admin" untuk kembali ke akun asli Anda.')
                        ->persistent()
                        ->warning()
                        ->send();

                    session(['login_as_notified' => true]);
                }
            }
        }

        return $next($request);
    }
}
