<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        // Assuming a phone link or similar for parent->children, else showing students in same tribe
        $children = User::role("student")->where("community_tribe", $user->community_tribe)->take(3)->get();
        return view("parent.dashboard", compact("children"));
    }
}
