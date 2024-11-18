<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function create()
    
    {
        return view('company.registercompany');
    }



    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'vat_number' => 'required|string|max:255',
        ]);
    
        Company::create([
            'company_name' => $request->company_name,
            'address' => $request->address,
            'vat_number' => $request->vat_number,
        ]);
    
          // Set a session variable to indicate form completion
          $request->session()->put('form_completed', true);
    
        // Redirect to the /register page
        return redirect()->route('register');
    }
    
    public function showRegisterPage(Request $request)
    {
        // Check if the form has been completed
        if ($request->session()->get('form_completed')) {
            return view('auth.register'); // Use dot notation for the directory structure
        }

        // If form not completed, redirect back to /company/registercompany
        return redirect()->route('registercompany');

    }
}

 