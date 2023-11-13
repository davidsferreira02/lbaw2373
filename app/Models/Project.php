<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Project extends Model
{
    protected $table = 'project';
    public $timestamps = false;

    protected $fillable = [
        'title', 'description', 'theme', 'archived',
    ];

    public function members()
    {
        return $this->belongsToMany(User::class, 'is_member', 'id_project', 'id_user');
    }

    public function leaders()
    {
        return $this->belongsToMany(User::class, 'is_leader', 'id_project', 'id_user');
    }
}
