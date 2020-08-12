<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\Language' => 'App\Policies\LanguagePolicy',
        'App\Section' => 'App\Policies\SectionPolicy',
        'App\Comment' => 'App\Policies\CommentPolicy',
        'App\Article' => 'App\Policies\ArticlePolicy',
        'App\ProgramSchedule' => 'App\Policies\ProgramSchedulePolicy',
        'App\Propaganda' => 'App\Policies\PropagandaPolicy',
        'App\Podcast' => 'App\Policies\PodcastPolicy',
        'App\Correo' => 'App\Policies\CorreoPolicy',
        'App\Gallery' => 'App\Policies\GalleryPolicy',
        'App\Registro' => 'App\Policies\BulletinPolicy',
        'App\Ribbon' => 'App\Policies\RibbonPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
