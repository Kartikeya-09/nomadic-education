<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view("auth.register");
    }

    public function register(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "login" => "required|string|max:255|unique:users,email", // Accepting email or phone as login
            "password" => "required|string|min:8",
            "role" => "required|in:student,teacher,parent",
        ]);

        $isEmail = filter_var($request->login, FILTER_VALIDATE_EMAIL);

        $user = User::create([
            "name" => $request->name,
            "email" => $isEmail ? $request->login : null,
            "guardian_phone" => !$isEmail ? $request->login : null,
            "password" => Hash::make($request->password),
            "community_tribe" => $request->community_tribe,
            "current_camp_location" => $request->current_camp_location,
        ]);

        // Spatie Assign Role
        $user->assignRole($request->role);

        Auth::login($user);

        return redirect()->route($request->role . ".dashboard");
    }
}
