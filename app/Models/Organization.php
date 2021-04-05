<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';
    public $timestamps = true;


    protected $fillable = [
        'title',
        'city',
        'country',
        'type_id',
        'description',
        'status'    
    ];


    // A user belongs to one organization and an organization has many users.
    /* public function organizations(){
         return $this->hasMany('App\Models\Organizations');
     }*/


    public function contacts()
    {
        return $this->hasOne('App\Models\Contact');
    }


    public function type()
    {
        return $this->belongsTo('App\Models\Organization_Type','type_id');
        
    }

    // A user belongs to one organization and an organization has many users.
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    //relation for activity organization
    public function land_parcel()
    {
        return $this->hasMany('App\Models\Land_Parcel');
    }

    public function environment_restorations()
    {
        return $this->hasMany('App\Models\Environment_Restoration');
    }

    //relation for m-m relationship between land_parcels and organizations
    public function land_parcels()
    {
        return $this->belongsToMany('App\Models\Land_Parcel','land_has_organizations','organization_id', 'land_parcel_id');
    }

    public function tree_removal_request(){
        return $this->hasMany('App\Models\Tree_Removal_Request');
    }

    public function development_project(){
        return $this->hasMany('App\Models\Development_Project');
    }
}
