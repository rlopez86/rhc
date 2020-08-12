<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LanguagePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {
        if ($user->superadmin) {
            return true;
        }
    }

    public function edit(User $user)
    {
        return $user->permissions->pluck('permit')->contains('manage_languages');
    }
}
