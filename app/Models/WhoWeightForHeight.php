<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk data referensi WHO Weight-for-Height (BB/TB)
 * Digunakan untuk mendeteksi wasting (kurus) pada balita.
 * Referensi diindeks berdasarkan tinggi badan (45–120 cm), bukan usia.
 */
class WhoWeightForHeight extends Model
{
    protected $table = 'who_weight_for_height';

    public $timestamps = false;

    protected $fillable = [
        'gender', 'height_cm',
        'l_value', 'm_value', 's_value',
        'sd_minus3', 'sd_minus2', 'sd_plus2', 'sd_plus3',
    ];

    protected $casts = [
        'height_cm' => 'float',
        'l_value' => 'float',
        'm_value' => 'float',
        's_value' => 'float',
        'sd_minus3' => 'float',
        'sd_minus2' => 'float',
        'sd_plus2' => 'float',
        'sd_plus3' => 'float',
    ];

    /**
     * Static cache to store reference data within the request lifecycle.
     *
     * @var array
     */
    protected static $cache = [];

    /**
     * Dapatkan referensi LMS untuk gender dan tinggi badan tertentu.
     * Menggunakan rounding ke 0.5 cm terdekat sesuai tabel WHO.
     *
     * @param  string  $gender  'M' atau 'F'
     * @param  float  $heightCm  Tinggi badan dalam cm
     */
    public static function getReference(string $gender, float $heightCm): ?self
    {
        // WHO WFH table menggunakan step 0.5 cm
        $rounded = round($heightCm * 2) / 2;
        $rounded = max(45.0, min(120.0, $rounded));
        $cacheKey = "{$gender}_{$rounded}";

        if (! isset(self::$cache[$cacheKey])) {
            self::$cache[$cacheKey] = self::where('gender', $gender)
                ->where('height_cm', $rounded)
                ->first();
        }

        return self::$cache[$cacheKey];
    }
}
