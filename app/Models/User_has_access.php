<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_has_access extends Model
{
    use HasFactory;
    protected $table = "user_has_access";
    
    public function access()
    {
        return $this->belongsTo('App\Models\Access');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
