<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Likes;

use Illuminate\Support\Facades\Auth;
class Comment extends Model
{
    use HasFactory;

   
    protected $fillable = [
        'content',
        'date'
    ];

    protected $dates = [
        'date'
    ];

    public $timestamps = false;

    protected $table = 'comment';

    protected $primaryKey = 'id';

   
    public function task()
    {
        return $this->belongsTo(Task::class, 'id_task');
    }
    public function owner()
    {
        return $this->belongsToMany(User::class, 'commentowner', 'id_comment', 'id_user');
    }

    public function likes()
    {
        return $this->hasMany(Likes::class);
    }
    public function likedByCurrentUser()
    {
        $user = Auth::user();
        
        if (!$user) {
            return false; 
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }
}
