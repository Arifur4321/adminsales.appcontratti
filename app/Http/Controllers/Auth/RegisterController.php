<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\Mail\AccountCreatedMail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    // Remove RegistersUsers trait to prevent default registration behavior
    // Redirect to the desired route after registration
    protected $redirectTo = '/Admin-List';

    public function __construct()
    {
        // Ensure only guests can access the registration page
        $this->middleware('guest')->except(['logout', 'register']);
    }

    // Validator for the registration form
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'dob' => ['required', 'date', 'before:today'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);
    }

    // Handle user creation without logging in
    protected function create(array $data)
    {
        // Handle avatar upload if provided
        if (request()->hasFile('avatar')) {
            $avatar = request()->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
        } else {
            $avatarName = 'default.png';
        }

        // Fetch the last row id from the companies table
        $lastCompany = DB::table('companies')->latest('id')->first();
        $companyId = $lastCompany ? $lastCompany->id : null;

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'dob' => date('Y-m-d', strtotime($data['dob'])),
            'avatar' => "/images/" . $avatarName,
            'company_id' => $companyId,
        ]);
    }
 
    public function register(Request $request)
    {
        // Validate the user data
        $this->validator($request->all())->validate();
    
        // Create the user
        $user = $this->create($request->all());
    
        // Prepare user details
        $name = $user->name;
        $email = $user->email;
        $password = $request->input('password'); // Use the original password
    
        // Send the custom email
        Mail::to($user->email)->send(new AccountCreatedMail($name, $email, $password));
    
        // Redirect with query parameter for success
        return redirect('/Admin-List?success=true');
    }
    


    // public function register(Request $request)
    // {
    //     // Validate the user data
    //     $this->validator($request->all())->validate();
    
    //     // Create the user
    //     $user = $this->create($request->all());
    
    //     // Prepare user details
    //     $name = $user->name;
    //     $email = $user->email;
    //     $password = $request->input('password'); // Use the original password
    
    //     // Send the custom email
    //     Mail::to($user->email)->send(new AccountCreatedMail($name, $email, $password));
    
    //     // Set a flash message for SweetAlert
    //     session()->flash('registration_success', 'New user registered successfully! Please check your email for details.');
    
    //     // Redirect to the Admin List or another appropriate page
    //     return redirect('/Admin-List');
    // }
 

} 
