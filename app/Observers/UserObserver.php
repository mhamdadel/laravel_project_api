<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        if(!is_null($user->email)) {
            $user->sendEmailVerificationNotification($user);
        }
    }
}
