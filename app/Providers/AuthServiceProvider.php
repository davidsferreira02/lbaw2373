<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Comment;
use App\Policies\CommentPolicy;
use App\Models\Task;
use App\Policies\TaskPolicy;
use App\Models\Project;
use App\Policies\ProjectPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        
        Task::class=>TaskPolicy::class,
        Project::class=>ProjectPolicy::class,
        Comment::class=>CommentPolicy::class,
        Favorite::class=>FavoritePolicy::class,
        Invite::class=>InvitePolicy::class,
        Likes::class=>LikesPolicy::class,
        User::class=>UserPolicy::class,
        


    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
