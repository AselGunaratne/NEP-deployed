<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'districts';

    protected $fillable = [
        'district', 'status', 'deleted_at', 'province_id'
    ];

    public function tree_removal_requests(){
        return $this->hasMany('App\Models\Tree_Removal_Request');
    }

    public function land_parcels(){
        return $this->hasMany('App\Models\Land_Parcel');
    }

    public function province()
    {
        return $this->belongsTo('App\Models\Province');
    }
}
