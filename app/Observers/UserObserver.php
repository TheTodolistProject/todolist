<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    public function creating(User $user): void
    {
        $user->slug = Str::slug($user->fname.' '.$user->lname, '-');
    }
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {

    }

    public function updating(User $user): void
    {
        $user->slug = Str::slug($user->fname.' '.$user->lname, '-');
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {

    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
