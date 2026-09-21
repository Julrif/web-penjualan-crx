<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user login dan role_id = 1 (admin), redirect ke dashboard
        if (Auth::check() && Auth::user()->role_id == 1) {
            // Cek apakah akses ke route keranjang
            if ($request->routeIs('user.keranjang.*')) {
                return redirect()->route('dashboard')->with('error', 'Admin tidak memiliki akses ke keranjang!');
            }
        }
        
        return $next($request);
    }
}