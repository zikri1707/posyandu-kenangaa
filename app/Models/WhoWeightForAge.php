<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhoWeightForAge extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'who_weight_for_age';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gender',
        'age_months',
        'sd_minus3',
        'sd_minus2',
        'median',
        'sd_plus2',
        'sd_plus3',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'age_months' => 'integer',
        'sd_minus3' => 'decimal:2',
        'sd_minus2' => 'decimal:2',
        'median' => 'decimal:2',
        'sd_plus2' => 'decimal:2',
        'sd_plus3' => 'decimal:2',
    ];

    /**
     * Static cache to store reference data within the request lifecycle.
     *
     * @var array
     */
    protected static $cache = [];

    /**
     * Get WHO reference data for specific gender and age
     *
     * @param  string  $gender  'M' for male, 'F' for female
     * @param  int  $ageMonths  Age in months (0-59)
     */
    public static function getReference(string $gender, int $ageMonths): ?self
    {
        $cacheKey = "{$gender}_{$ageMonths}";

        if (! isset(self::$cache[$cacheKey])) {
            self::$cache[$cacheKey] = self::where('gender', $gender)
                ->where('age_months', $ageMonths)
                ->first();
        }

        return self::$cache[$cacheKey];
    }
}
