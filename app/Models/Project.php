<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projectt';
    public $timestamps  = false;


    public function owners()
    {
        return $this->hasMany('App\Models\Owner', 'id_project');
    }

  

  

    
    
}