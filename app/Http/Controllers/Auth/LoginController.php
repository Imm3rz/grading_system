<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/students';

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    // 👇 Add this method
    public function showLoginForm()
{
    if (auth()->check()) {
        return redirect('/students');
    }

    return view('auth.login'); 
}

}
