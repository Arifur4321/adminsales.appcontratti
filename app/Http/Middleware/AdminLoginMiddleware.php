<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminLoginMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        if (auth()->check()) {
            return $next($request); // Allow access if logged in
        }
    
        // If not logged in, save the intended URL and redirect to login
        $request->session()->put('url.intended', $request->url());
        return redirect()->route('admin.login');
    }
    



    // public function handle(Request $request, Closure $next)
    // {
    //     // Check if the admin is logged in and if the session is still valid
    //     $loginTime = $request->session()->get('admin_login_time');
    //     $now = Carbon::now();

    //     // Extend session timeout to 30 minutes
    //     if ($loginTime && $now->diffInMinutes($loginTime) < 2) {
    //         return $next($request);
    //     }

    //     // If not logged in or session has expired, forget the session and redirect to login
    //     $request->session()->forget('admin_logged_in');
    //     $request->session()->forget('admin_login_time');
    //     return redirect()->route('admin.login');
    // }


    // public function handle(Request $request, Closure $next)
    // {
    //     // Check if the admin is logged in and if the session is still valid
    //     $loginTime = $request->session()->get('admin_login_time');
    //     $now = Carbon::now();

    //     if ($loginTime && $now->diffInMinutes($loginTime) < 1) {
    //         return $next($request);
    //     }

    //     // If not logged in or session has expired, forget the session and redirect
    //     $request->session()->forget('admin_logged_in');
    //     $request->session()->forget('admin_login_time');
    //     return redirect()->route('admin.login');
    // }
}
