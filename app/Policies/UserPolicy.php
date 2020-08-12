<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->superadmin) {
            return true;
        }
    }

    public function view(User $user)
    {
        return ($user->permissions->pluck('permit')->contains('manage_users') or $user->permissions->pluck('permit')->contains('manage_permissions'));
    }

    public function edit(User $user)
    {
        return $user->permissions->pluck('permit')->contains('manage_users');
    }

    public function permissions(User $user)
    {
        return $user->permissions->pluck('permit')->contains('manage_permissions');
    }
}
