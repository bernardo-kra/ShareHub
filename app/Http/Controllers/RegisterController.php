<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{

    public function show()
    {
        return view('layouts.auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        auth()->login($user);

        return redirect('/')->with('success', "Account successfully registered.");
    }
}
