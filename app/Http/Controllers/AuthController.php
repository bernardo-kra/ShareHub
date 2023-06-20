<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('login', $request->login)->first();


        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                Session::put('user', $user);
                return redirect('home');
            } else {
                return back()->with('error', 'Wrong password!');
            }
        } else {
            return back()->with('error', 'User not found!');
        }
    }

    public function logout()
    {
        Session::forget('user');
        return redirect('login');
    }
}
