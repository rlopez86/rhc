<?php

namespace App\Policies;

use App\Article;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
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
        return true;
    }

    public function create(User $user){
        return $user->permissions->pluck('permit')->contains('create_article');
    }

    /*
     *  TODO: Most of this methods need to receive the article over you have to perform the action in order to check pertenencia
    */

    public function edit(User $user, Article $article){
        if(!$user->permissions->pluck('permit')->contains('edit_article')){
            return $this->selfEdit($user) && $user->id == $article->autor;
        }
        return false;
    }

    public function selfEdit(User $user){
        return $user->permissions->pluck('permit')->contains('edit_own_article');
    }

    public function publish(User $user, Article $article){
        if(!$user->permissions->pluck('permit')->contains('publish_article')){
            return $this->selfpublish($user) && $user->id == $article->autor;
        }
        return false;
    }

    public function selfPublish(User $user){
        return $user->permissions->pluck('permit')->contains('publish_own_article');
    }

    public function delete(User $user, Article $article){
        if(!$user->permissions->pluck('permit')->contains('delete_article')){
            return $this->selfpublish($user) && $user->id == $article->autor;
        }
    }

    public function selfDelete(User $user){
        return $user->permissions->pluck('permit')->contains('delete_own_article');
    }

    public function disableComments(User $user){
        return $user->permissions->pluck('permit')->contains('disable_comments');
    }

    public function move(User $user){
        return $user->permissions->pluck('permit')->contains('article_to_section');
    }

    public function selfMove(User $user){
        return $this->move($user) || $user->permissions->pluck('permit')->contains('own_article_to_section');
    }

    public function highlight(User $user){
        return $user->permissions->pluck('permit')->contains('higlight_article');
    }

    public function setPosition(User $user){
        return $user->permissions->pluck('permit')->contains('change_article_position');
    }

    public function save(User $user){
        $permits = $user->permissions->pluck('permit');
        return $permits->contains('edit_article')|| $permits->contains('edit_article');

    }
}
