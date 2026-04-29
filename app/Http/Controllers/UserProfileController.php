<?php
 
namespace App\Http\Controllers;
 
use App\Models\User;
 
class UserProfileController extends Controller
{
    public function show(User $user)
    {
        return view('simple.users.show', compact('user'));
    }
}
 