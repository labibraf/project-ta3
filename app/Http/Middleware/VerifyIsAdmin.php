<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class VerifyIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Periksa dulu apakah user sudah login atau belum
        if (!Auth::check()) {
            // Jika belum login, arahkan ke halaman login
            return redirect()->route('login');
        }
        $role_id = $request->user()->role_id;
        
        if (Auth::id() != 1 && $role_id != Role::where('role_name', 'Admin')->first()->id) {
            Alert::error('Akses Ditolak', 'Anda tidak memiliki izin.');
            return redirect()->route('home');
        }
        return $next($request);
    

        // $role_id = $request->user()->role_id;
        // $Adminid = Role::where('role_name', 'Admin')->first()->id;

        // if ($role_id != $Adminid) {
        //     Alert::error('Anda tidak memiliki akses');
        //     return redirect()->route('home');
        // }
        // return $next($request);
    }
}
