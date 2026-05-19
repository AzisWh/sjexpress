<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! Auth::check()) {

            Alert::error('Access Denied', 'Kamu harus login.');

            return redirect()->route('login-view');
        }

        if (! empty($roles) && ! in_array(Auth::user()->role, $roles)) {

            Alert::error('Access Denied', 'Kamu tidak punya akses.');

            return redirect()->back();
        }

        return $next($request);
    }
}
