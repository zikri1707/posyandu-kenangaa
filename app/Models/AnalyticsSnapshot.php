<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsSnapshot extends Model
{
    protected $fillable = [
        'posyandu_id',
        'key',
        'data',
        'last_computed_at',
    ];

    protected $casts = [
        'data' => 'array',
        'last_computed_at' => 'datetime',
    ];

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }
}
