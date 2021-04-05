<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Species_Information extends Model
{
    use HasFactory;
    protected $table = 'species_information';

    protected $fillable = [
        'title',
    ];

    public function environment_restoration_species()
    {
        return $this->hasMany('App\Models\EnvironmentRestorationSpecies');
    }
}
