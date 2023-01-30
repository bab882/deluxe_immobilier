<?php

namespace App\Http\Controllers\Auth;

class LoginController
{
    private $redirectTo = "/home";
    private $redirectToAdmin = "/admin";

    public function view()
    {
        return view('auth.login');
    }
    
    // systeme pour se logger
    public function login()
    {
        Auth::logout();
        $request = new LoginRequest();
        
    }
}