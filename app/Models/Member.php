<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;
    public $timestamps  = false;
    public $incrementing = false;
    protected $table = 'isMember';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }


    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'id_project');
    }
}
