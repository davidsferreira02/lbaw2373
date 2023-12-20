<?php

namespace App\Http\Controllers;

use App\Mail\MailModel;
use App\Models\User;
use Illuminate\Support\Facades\Mail as Mail;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class MailController extends Controller
{
    // sendEmail method.
    public function recoverEmail($username, $email, $token)
    {

        $token_link = config('app.url') . '/password/reset/' . $token;

   

        $mailData = [
            'name' => $username,
            'email' => $email,
            'token_link' => $token_link
        ];

        Mail::to($mailData['email'])->send(new MailModel($mailData));
    }

    public function passwordRequest(Request $request)
    {
  
        $user = DB::table('users')->where('email', '=', $request->email)
            ->first();


        if ($user === null) {
            return redirect()->back()->with('error', 'There is no user with this email'); 
        }

       
        DB::table('password_reset')->insert([
            'email' => $user->email,
            'token' => Str::random(40),
        ]);

 
        $tokenData = DB::table('password_reset')->where('email', $request->email)->first();

        $this->recoverEmail($user->username, $user->email, $tokenData->token);
        return redirect()->route('forgot-password-sent');
    }

    public function showChangePassword()
    {
        if (!auth()->check())
            return redirect()->route('login');

        $token = Str::random(40);

        DB::table('password_reset')->insert([
            'email' => Auth::user()->email,
            'token' => $token,
        ]);

        return view('auth.password-reset', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {

     

        if($request->password != $request->password_confirmation)
            return redirect()->back()->with('error', 'Passwords do not match'); 

        if(strlen($request->password) < 8)
            return redirect()->back()->with('error', 'Password needs to be at least 8 characters long');

        $password = $request->password;

       
        $tokenData = DB::table('password_reset')->where('token', $request->token)->first();

        
        if ($tokenData === null) return view('auth.password-reset', ['token' => 'invalid_token']);

        $user = User::where('email', $tokenData->email)->first();
        if ($user === null) return redirect()->route('login');

        $user->password = Hash::make($password);

        $user->update();

       
        Auth::login($user);

    
        DB::table('password_reset')->where('email', $user->email)->delete();

        return redirect()->route('project.home');
    }
}
