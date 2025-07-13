<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posyandu extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedukuhan_id', 'name', 'address', 'unique_code', 'logo_photo'
    ];

    // Relationship with Pedukuhan
    public function pedukuhan()
    {
        return $this->belongsTo(Pedukuhan::class);
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Schedule
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // Relationship with Gallery
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    // Relationship with Patient
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
