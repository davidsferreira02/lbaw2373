<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IsAdmin extends Model

{

    protected $table = 'isadmin';
    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
