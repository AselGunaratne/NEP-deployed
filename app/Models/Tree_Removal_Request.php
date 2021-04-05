<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tree_Removal_Request extends Model
{
    use HasFactory;

    protected $table = 'tree_removal_requests';

    protected $fillable = [
        'created_by_user_id',
        'description',
        'land_size',
        'no_of_trees',
        'no_of_tree_species',
        'no_of_mammal_species',
        'no_of_amphibian_species',
        'no_of_reptile_species',
        'no_of_avian_species',
        'no_of_flora_species',
        'species_special_notes',
        'status_id',
        'land_parcel_id',
        'district_id',
        'gs_division_id',
        'organization_id',
        'images',
        'tree_details',
    ];

    protected $casts = [
        'tree_details' => 'array',
        'governing_organizations' => 'array',
    ];

    protected $attributes = [
        
        'tree_details' => 0,
        'status_id' => 1,
        'land_size' => 0,
        'no_of_tree_species' => 0,
        'no_of_mammal_species' => 0,
        'no_of_amphibian_species' => 0,
        'no_of_reptile_species' => 0,
        'no_of_avian_species' => 0,
        'no_of_flora_species' => 0,
        'species_special_notes' => 0,
    ];


    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }

    public function gs_division()
    {
        return $this->belongsTo('App\Models\GS_Division');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District');
    }

    public function land_parcel()
    {
        return $this->belongsTo('App\Models\Land_Parcel');
    }

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

}
