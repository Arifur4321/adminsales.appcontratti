<?php
  

  namespace App\Http\Controllers;

  use Illuminate\Http\Request;
  use Carbon\Carbon;

  class AdminController extends Controller
  {
 
 
    public function showLoginForm()
    {
        // Check if the user is authenticated
        if (auth()->check()) {
            // Fetch the currently authenticated user
            $user = auth()->user();

            // Check if the user belongs to company_id 1
            if ($user->company_id == 1) {
                // If the user is from company_id 1, redirect to 'registercompany'
                return redirect()->route('registercompany');
            }
        }

        // If the user is not logged in or doesn't belong to company_id 1, show the login form
        return view('auth.login'); // Customize this as needed
    }


      public function login(Request $request)
        {
            // Add your login validation logic here

            // If login is successful
            $request->session()->put('admin_logged_in', true);
            $request->session()->put('admin_login_time', Carbon::now());

            // Check if the user was trying to access /company/registercompany before logging in
            if ($request->session()->has('url.intended')) {
                return redirect()->intended();
            }

            // Default redirect after login
            return redirect()->route('admin.dashboard');
        }
 
      public function logout(Request $request)
      {
          $request->session()->forget('admin_logged_in');
          $request->session()->forget('admin_login_time');
          return redirect()->route('admin.login');
      }
  }
  