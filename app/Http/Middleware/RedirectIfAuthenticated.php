<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // if (Auth::guard($guard)->check()) {
        //     return redirect(RouteServiceProvider::HOME);
        // }
        if (Auth::guard($guard)->check()) {
            if(Auth::user()->usertype=='admin'){
                return redirect()->intended('/home#/admin/dashboard');
            }
            elseif(Auth::user()->usertype=='staff'){
                return redirect()->intended('/home#/staff/dashboard');
            }
            elseif(Auth::user()->usertype=='student'){
                return redirect()->intended('/home#/student/dashboard');
            }else{
                return redirect(RouteServiceProvider::HOME);
            }
        }
        

        return $next($request);
    }
}
