<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class LoginController extends Controller
{
     
    use AuthenticatesUsers;

 
    protected $redirectTo = RouteServiceProvider::HOME;

  
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        // Find the user with the provided email and company_id = 1
        $user = User::where('email', $request->email)
                    ->where('company_id', 1)
                    ->first();

        // If the user exists and the password is correct, log them in
        if ($user && Hash::check($request->password, $user->password)) {
            return $this->guard()->login($user, $request->filled('remember'));
        }

        return false;
    }
  
       // send failed for login 
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => trans('auth.failed'),
            ]);
    }

}
