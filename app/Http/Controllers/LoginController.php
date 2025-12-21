<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show() {
        return view('pages.auth.login'); // Arahkan ke file login buatan Anda
    }

    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // LOGIKA REDIRECT BERDASARKAN ROLE
            if ($user->role == 'superadmin') {
                return redirect()->route('dashboard'); // Admin ke Dashboard Utama
            } else {
                return redirect()->route('kel_barang.b_masuk.index'); 
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('auth/login');
    }
}