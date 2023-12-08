<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\View\View;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display a login form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'username' => ['required', 'string', 'min:6', 'unique:users', 'regex:/^(?=.*\d)[a-zA-Z0-9]{6,}$/'], 
            'password' => 'required|min:8|confirmed'
        ], [
            'username.regex' => 'O campo username deve ter no mínimo 6 caracteres e conter pelo menos um número.'
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => $request->username // Certifique-se de incluir o campo username aqui
        ]);
        
        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('project.home')
            ->withSuccess('You have successfully registered & logged in!');
    }
}
