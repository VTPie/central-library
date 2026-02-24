<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/books');
        }

        return back()->withErrors([
            'email' => 'Email or Password is incorrect.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function register(RegisterRequest $request)
    {
        try {
            $userInfo = $request->validated();
            $this->userService->createUser($userInfo);

            return redirect('/login')->with('success', 'User registered successfully. Please log in again!');
        } catch (\Exception $e){
            Log::error($e->getMessage());

            return redirect('/login')->with('error', 'Failed to register new user. Please try again.');
        }
    }
}
