<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Comment;
use App\Models\IsAdmin;
use App\Models\Likes;
use App\Policies\CommentPolicy;
use App\Models\Task;
use App\Policies\LikePolicy;
use App\Policies\TaskPolicy;
use App\Models\Project;
use App\Policies\ProjectPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Policies\AdminPolicy;
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
        User::class=>UserPolicy::class,
        IsAdmin::class=>AdminPolicy::class,
       
        


    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
