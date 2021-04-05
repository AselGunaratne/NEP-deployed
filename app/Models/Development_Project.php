<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Development_Project extends Model
{
    use HasFactory;
    protected $table = 'development_projects';

    protected $fillable = [
        'title',
        'gazette_id',
        'organization_id',
        'land_parcel_id',
        'protected_area',
        'created_by_user_id',
        'status_id',
        'description',
        'images',
        'land_size',
    ];

    protected $attributes = [
        'protected_area' => 0,
        'status_id' => 1,
        'land_size' => 0,
        'images' => 0,
    ];

    

    public function gazette()
    {
        return $this->belongsTo('App\Models\Gazette');
    }

    public function land_parcel()
    {
        return $this->belongsTo('App\Models\Land_Parcel');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

}
