<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $table = 'task';
    public $timestamps = false;
    protected $fillable = [
        'title',
        'content',
        'priority',
        'dateCreation',
        'deadLine',
        'id_project', 
    ];

    protected $casts = [
        'dateCreation' => 'datetime',
        'deadLine' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project');
    }

 

    public function assigned() {
        return $this->belongsToMany(User::class, 'assigned', 'id_task', 'id_user');
    }

    public function owners() {
        return $this->belongsToMany(User::class, 'taskowner', 'id_task', 'id_user');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_task'); // Defina o nome correto da chave estrangeira aqui
    }
}
