<?php

// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');

// });
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // 🔥 redirect berdasarkan role
            if ($user->role === \App\Enums\Role::OPD) {
                return redirect('/opd');
            }

            if ($user->role === \App\Enums\Role::ADMIN || $user->role === \App\Enums\Role::SUPER_ADMIN) {
                return redirect('/admin1');
            }

            Auth::logout();
            return back()->with('error', 'Role tidak dikenali');
        }

        return back()->with('error', 'Login gagal');
    })->name('login.post');