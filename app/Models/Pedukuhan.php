<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedukuhan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'postal_code', 'geo_location'
    ];

    // Relationship with Posyandu
    public function posyandus()
    {
        return $this->hasMany(Posyandu::class);
    }
}
