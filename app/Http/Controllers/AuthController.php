<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ============================================
    // LOGIN
    // ============================================
    public function login()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                return redirect()->route('dashboard');
            }
            return redirect()->route('products.index');
        }
        
        return view('pages.login');
    }

    public function auth(Request $req)
    {
        $credential = $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credential)) {
            // Cek apakah email sudah diverifikasi
            if (is_null(Auth::user()->email_verified_at)) {
                Auth::logout();
                return redirect()->route('verification.notice')
                    ->with('error', 'Email belum diverifikasi! Silakan cek email Anda.');
            }

            $req->session()->regenerate();
            
            // Jika admin (role_id = 1) → dashboard
            if (Auth::user()->role_id == 1) {
                return redirect()->route('dashboard');
            }
            
            // Jika user biasa (role_id = 2) → halaman produk
            return redirect()->route('products.index');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!'
        ]);
    }

    // ============================================
    // LOGOUT
    // ============================================
    public function logout(Request $req)
    {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect('/')->with('success', 'Berhasil logout!');
    }

    // ============================================
    // REGISTER
    // ============================================
    public function showRegister()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                return redirect()->route('dashboard');
            }
            return redirect()->route('products.index');
        }
        
        return view('pages.auth.register');
    }

    public function register(Request $req)
    {
        $req->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
            'role_id' => 2,
        ]);

        // Kirim email verifikasi
        $user->sendEmailVerificationNotification();

        // Login dulu biar bisa akses verify page
        Auth::login($user);

        // Redirect ke halaman verifikasi
        return redirect()->route('verification.notice')
            ->with('success', 'Pendaftaran berhasil! Silakan cek email Anda untuk verifikasi.');
    }
}