<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     *@param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $roleMap = config('roles');
            $userRole = $roleMap[Auth::user()->role] ?? 'unknown';

            // Example: Redirect based on roles
            if ($userRole === 'admin') {
                return redirect('/admin/dashboardA');
            } elseif ($userRole === 'recruiter') {
                return redirect('/recruiter/dashboardR');
            } elseif ($userRole === 'user') {
                return redirect('/home');
            }
        }

        if (!auth()->check()) {
            Log::info('User is not authenticated. Redirecting to login.');
            return redirect('/login');
        }

        $user = auth()->user();
        if ($user->spatie_role !== $role) {
            Log::info('User with role: ' . $user->spatie_role . ' tried to access a restricted route for role: ' . $role);
            return redirect('/home');
        }

        Log::info('User with role: ' . $user->spatie_role . ' is allowed to access this route.');
        return $next($request);
        
    }
}
