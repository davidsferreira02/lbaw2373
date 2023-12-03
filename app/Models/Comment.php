<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
