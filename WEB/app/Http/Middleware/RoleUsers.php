<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, String $role): Response
    {
        if(Auth::user()->role === 'admin'){
            return $next($request);
        }

        if(Auth::user()->role !== $role){
            return redirect('/home')->with('error', 'Anda tidak memiliki hak akses');
        }

        return $next($request);
    }
}
