<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check User
        $user = Auth::user();
        if (!$user) {
            session()->flash('action_message', 'login_required');
            return redirect()->route('login');
        }
        
        // Check Role
        if ($user['role'] != 'super_admin') {
            session()->flash('action_message', 'role_only_super_admin');
            return redirect()->route('login');
        }

        return $next($request);
    }
}
