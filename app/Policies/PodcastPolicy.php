<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PodcastPolicy
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

    public function read(User $user){
        return $this->manage_podcasts($user) || $this->manage_programs($user);
    }

    public function manage_podcasts(User $user){
        return $user->permissions->pluck('permit')->contains('manage_podcasts');
    }

    public function manage_programs(User $user){
        return $user->permissions->pluck('permit')->contains('manage_programs');
    }
}
