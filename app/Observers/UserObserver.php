<?php
namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    public function updated(User $user): void {
        if ($user->wasChanged(['pseudo', 'avatar', 'birth_date', 'gender'])) {
            Cache::forget("user.{$user->id}.age");
            Cache::forget("user.{$user->id}.profile");
        }
    }

    public function created(User $user): void {
        if ($user->role === User::ROLE_VISITOR) {
            $user->updateQuietly(['is_approved' => true]);
        }
    }
}