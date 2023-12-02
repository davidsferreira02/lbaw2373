<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{

    public $timestamps = false;
    protected $table = 'favorite';
    protected $fillable = [
        'project_id',
        'users_id',
    ];

    
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
