<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();

        // Redirect based on the user's role
        $url = '';

        switch ($request->user()->role) {
            case 'admin':
                // Redirect to the admin dashboard
                $url = 'admin/dashboardA';
                break;

            case 'recruiter':
                // Redirect to the recruiter dashboard
                $url = 'recruiter/dashboardR';
                break;

            case 'user':
                // Redirect to the staff dashboard
                $url = 'user/dashboardU';
                break;

            default:
                // Redirect to a default page if role is not found
                $url = 'home';
                break;
        }

        // Redirect to the appropriate dashboard
        return redirect()->intended($url);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
