<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;
    protected $table = 'project';
    public $timestamps  = false;


    public function leader()
    {
        return $this->belongsToMany('App\Models\User','isLeader','id_user','id_project');
    }

    public function member() {
        return $this->belongsToMany('App\Models\User','isMember','id_user','id_project');
        }


        public function isLeader(User $user){
        
            foreach($this->leaders as $leaders){
                if($user->id==$leaders->id){
                    return TRUE;
                }
            }
            return FALSE;
        }
    
        public function isMember($email){
            foreach($this->members as $member){
                if(strcmp($member->email,$email)==0){
                    return TRUE;
                }
            }
            return FALSE;
        }
  

  

    
    
}