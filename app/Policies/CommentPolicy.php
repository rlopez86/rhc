<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function before($user, $ability)
    {
        if ($user->superadmin) {
            return true;
        }
    }

    public function read(User $user){
        return $this->publish($user) || $this->delete($user);
    }

    public function publish(User $user)
    {
        return $user->permissions->pluck('permit')->contains('publish_comment');
    }

    public function delete(User $user)
    {
        return $user->permissions->pluck('permit')->contains('delete_comment');
    }

    public function disable(User $user){
        return $user->permissions->pluck('permit')->contains('disable_comments');
    }
}
