<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Thread;
use App\Post;
use App\User;

use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerThreadPolicies();

        Schema::defaultStringLength(191);
    }

    public function registerThreadPolicies()
    {
        Gate::define('create-thread', function ($user) {
            return $user->hasAccess(['create-thread']);
        });
        Gate::define('edit-thread', function ($user) {
            return $user->hasAccess(['edit-thread']);
        });
        Gate::define('update-thread', function ($user, Thread $thread) {
            return $user->hasAccess(['update-thread']);
        });
        Gate::define('publish-thread', function ($user) {
            return $user->hasAccess(['publish-thread']);
        });
        Gate::define('see-all-drafts', function ($user) {
            return $user->inRole('administrator');
        });

        Gate::define('create-post', function ($user) {
            return $user->hasAccess(['create-post']);
        });
        Gate::define('edit-post', function ($user) {
            return $user->hasAccess(['edit-post']);
        });
        Gate::define('update-post', function ($user, Post $post) {
            $thread = Thread::where('id', $post->thread_id)->get()[0];

             return $user->hasAccess(['update-post']);
        });
        Gate::define('publish-post', function ($user) {
            return $user->hasAccess(['publish-post']);
        });
        Gate::define('see-all-drafts', function ($user) {
            return $user->inRole('editor');
        });
    }
}
