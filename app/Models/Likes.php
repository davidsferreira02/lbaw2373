<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    protected $fillable = ['comment_id', 'user_id'];
    public $timestamps = false; // Desabilita timestamps

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
