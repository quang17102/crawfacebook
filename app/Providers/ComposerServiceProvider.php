<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer(
            [
                'admin.sidebar',
                'user.linkfollow.list',
                'user.linkrunning.list',
                'user.linkscan.list',
                'user.comment.list',
                'user.reaction.list',
                'user.home',
            ],
            'App\Http\ViewComposers\RoleComposer'
        );
    }
}
