<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\FileController;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

  
    
    public function projectMember()
    {
        return $this->belongsToMany(Project::class, 'is_member', 'id_user', 'id_project');
    }
    public function projectLeader()
    {
        return $this->belongsToMany(Project::class, 'is_leader', 'id_user', 'id_project');
    }
    public function favoriteProjects()
    {
        return $this->belongsToMany(Project::class, 'favorite', 'users_id', 'project_id');
    }

    public function isAdmin()
    {
        return $this->isAdminRelation()->exists();
    }

  
    public function isAdminRelation()
    {
        return $this->hasOne(IsAdmin::class);
    }
    public function getProfileImage()
    {
        if ($this->photo && $this->photo!='profile/default.jpg') {
            return asset('img/profile/' . $this->photo);
        }

        // Retorne uma imagem padrão, se o usuário não tiver foto
        return asset('img/profile/default.jpg');
    }

    

    public function tasksOwned(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'taskowner', 'id_user', 'id_task');
    }

    public function tasksAssigned(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'assigned', 'id_user', 'id_task');
    }

    public function ownedComments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'commentowner', 'id_user', 'id_comment');
    }

    public function likes()
{
    return $this->hasMany(Likes::class, 'user_id');
}

public function favorites()
{
    return $this->hasMany(Favorite::class, 'users_id');
}
}
