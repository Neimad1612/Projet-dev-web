<?php
 
namespace App\Http\Controllers;
 
use App\Models\User;
 
class UserProfileController extends Controller
{
    public function show(User $user)
    {
        return view('simple.users.show', compact('user'));
    }
    public function index()
    {
    $users = User::approved()->latest()->paginate(20);
    return view('simple.users.index', compact('users'));
    }
}
 