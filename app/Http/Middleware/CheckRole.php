<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next)
    {
        // Jika user login dan role_id = 2 (user biasa), redirect ke halaman produk
        if (Auth::check() && Auth::user()->role_id == 2) {
            return redirect()->route('products.index')
                ->with('error', 'Anda tidak memiliki akses ke dashboard!');
        }

        return $next($request);
    }
}