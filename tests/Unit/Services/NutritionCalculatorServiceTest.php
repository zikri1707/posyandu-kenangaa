<?php

use App\Models\WhoWeightForAge;
use App\Services\NutritionCalculatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed sample WHO reference data for testing
    WhoWeightForAge::create([
        'gender' => 'M',
        'age_months' => 0,
        'sd_minus3' => 2.1,
        'sd_minus2' => 2.5,
        'median' => 3.3,
        'sd_plus2' => 4.4,
        'sd_plus3' => 5.0,
    ]);

    WhoWeightForAge::create([
        'gender' => 'F',
        'age_months' => 0,
        'sd_minus3' => 2.0,
        'sd_minus2' => 2.4,
        'median' => 3.2,
        'sd_plus2' => 4.2,
        'sd_plus3' => 4.8,
    ]);

    WhoWeightForAge::create([
        'gender' => 'M',
        'age_months' => 12,
        'sd_minus3' => 7.7,
        'sd_minus2' => 8.6,
        'median' => 10.2,
        'sd_plus2' => 12.0,
        'sd_plus3' => 13.3,
    ]);

    WhoWeightForAge::create([
        'gender' => 'F',
        'age_months' => 12,
        'sd_minus3' => 7.0,
        'sd_minus2' => 7.9,
        'median' => 9.5,
        'sd_plus2' => 11.3,
        'sd_plus3' => 12.5,
    ]);

    WhoWeightForAge::create([
        'gender' => 'M',
        'age_months' => 59,
        'sd_minus3' => 13.1,
        'sd_minus2' => 14.8,
        'median' => 18.3,
        'sd_plus2' => 22.9,
        'sd_plus3' => 25.5,
    ]);

    WhoWeightForAge::create([
        'gender' => 'F',
        'age_months' => 59,
        'sd_minus3' => 12.1,
        'sd_minus2' => 13.7,
        'median' => 17.9,
        'sd_plus2' => 23.2,
        'sd_plus3' => 26.0,
    ]);

    $this->service = new NutritionCalculatorService;
});

describe('calculateZScore', function () {
    it('menghitung z-score untuk bayi laki-laki usia 0 bulan', function () {
        $zScore = $this->service->calculateZScore(3.3, 0, 'L');

        expect($zScore)->toBeFloat()
            ->and($zScore)->toBe(0.0);
    });

    it('menghitung z-score untuk bayi perempuan usia 0 bulan', function () {
        $zScore = $this->service->calculateZScore(3.2, 0, 'P');

        expect($zScore)->toBeFloat()
            ->and($zScore)->toBe(0.0);
    });

    it('menghitung z-score untuk balita laki-laki usia 12 bulan', function () {
        $zScore = $this->service->calculateZScore(10.2, 12, 'M');

        expect($zScore)->toBeFloat()
            ->and($zScore)->toBe(0.0);
    });

    it('menghitung z-score untuk balita perempuan usia 12 bulan', function () {
        $zScore = $this->service->calculateZScore(9.5, 12, 'F');

        expect($zScore)->toBeFloat()
            ->and($zScore)->toBe(0.0);
    });

    it('menghitung z-score untuk balita laki-laki usia 59 bulan (edge case maksimal)', function () {
        $zScore = $this->service->calculateZScore(18.3, 59, 'L');

        expect($zScore)->toBeFloat()
            ->and($zScore)->toBe(0.0);
    });

    it('menghitung z-score untuk balita perempuan usia 59 bulan (edge case maksimal)', function () {
        $zScore = $this->service->calculateZScore(17.9, 59, 'P');

        expect($zScore)->toBeFloat()
            ->and($zScore)->toBe(0.0);
    });

    it('menghitung z-score positif untuk berat badan di atas median', function () {
        // Berat 12.0 kg (sd_plus2) untuk laki-laki 12 bulan
        $zScore = $this->service->calculateZScore(12.0, 12, 'M');

        expect($zScore)->toBeFloat()
            ->and($zScore)->toBeGreaterThan(0);
    });

    it('menghitung z-score negatif untuk berat badan di bawah median', function () {
        // Berat 8.6 kg (sd_minus2) untuk laki-laki 12 bulan
        $zScore = $this->service->calculateZScore(8.6, 12, 'M');

        expect($zScore)->toBeFloat()
            ->and($zScore)->toBeLessThan(0);
    });

    it('menerima format gender L (Laki-laki)', function () {
        $zScore = $this->service->calculateZScore(10.2, 12, 'L');

        expect($zScore)->not->toBeNull();
    });

    it('menerima format gender P (Perempuan)', function () {
        $zScore = $this->service->calculateZScore(9.5, 12, 'P');

        expect($zScore)->not->toBeNull();
    });

    it('menerima format gender M (Male)', function () {
        $zScore = $this->service->calculateZScore(10.2, 12, 'M');

        expect($zScore)->not->toBeNull();
    });

    it('menerima format gender F (Female)', function () {
        $zScore = $this->service->calculateZScore(9.5, 12, 'F');

        expect($zScore)->not->toBeNull();
    });

    it('mengembalikan null jika data referensi tidak ditemukan (usia di luar rentang)', function () {
        $zScore = $this->service->calculateZScore(20.0, 60, 'M');

        expect($zScore)->toBeNull();
    });

    it('mengembalikan null jika gender tidak valid', function () {
        $zScore = $this->service->calculateZScore(10.0, 12, 'X');

        expect($zScore)->toBeNull();
    });

    it('membulatkan z-score ke 2 desimal', function () {
        $zScore = $this->service->calculateZScore(10.5, 12, 'M');

        expect($zScore)->toBeFloat();

        // Verify it has at most 2 decimal places
        $decimalPart = $zScore - floor($zScore);
        $decimalPlaces = strlen(substr(strrchr((string) $decimalPart, '.'), 1));
        expect($decimalPlaces)->toBeLessThanOrEqual(2);
    });
});

describe('classifyNutritionStatus', function () {
    it('mengklasifikasikan z-score < -3 sebagai Gizi Buruk', function () {
        $status = $this->service->classifyNutritionStatus(-3.5);

        expect($status)->toBe('Gizi Buruk');
    });

    it('mengklasifikasikan z-score -3 sebagai Gizi Kurang', function () {
        $status = $this->service->classifyNutritionStatus(-3.0);

        expect($status)->toBe('Gizi Kurang');
    });

    it('mengklasifikasikan z-score -2.5 sebagai Gizi Kurang', function () {
        $status = $this->service->classifyNutritionStatus(-2.5);

        expect($status)->toBe('Gizi Kurang');
    });

    it('mengklasifikasikan z-score -2 sebagai Gizi Baik', function () {
        $status = $this->service->classifyNutritionStatus(-2.0);

        expect($status)->toBe('Gizi Baik');
    });

    it('mengklasifikasikan z-score 0 sebagai Gizi Baik', function () {
        $status = $this->service->classifyNutritionStatus(0.0);

        expect($status)->toBe('Gizi Baik');
    });

    it('mengklasifikasikan z-score 2 sebagai Gizi Baik', function () {
        $status = $this->service->classifyNutritionStatus(2.0);

        expect($status)->toBe('Gizi Baik');
    });

    it('mengklasifikasikan z-score > 2 sebagai Gizi Lebih', function () {
        $status = $this->service->classifyNutritionStatus(2.5);

        expect($status)->toBe('Gizi Lebih');
    });

    it('mengklasifikasikan null sebagai Tidak Dapat Dihitung', function () {
        $status = $this->service->classifyNutritionStatus(null);

        expect($status)->toBe('Tidak Dapat Dihitung');
    });
});

describe('calculate (combined method)', function () {
    it('mengembalikan array dengan z_score dan status', function () {
        $result = $this->service->calculate(10.2, 75.0, 12, 'M');

        expect($result)->toBeArray()
            ->and($result)->toHaveKeys(['z_score', 'status'])
            ->and($result['z_score'])->toBeFloat()
            ->and($result['status'])->toBeString();
    });

    it('menghitung z-score dan status yang konsisten', function () {
        $result = $this->service->calculate(8.6, 75.0, 12, 'M');

        expect($result['z_score'])->toBeLessThan(0)
            ->and($result['status'])->toBeIn(['Gizi Kurang', 'Gizi Buruk', 'Gizi Baik']);
    });

    it('menangani kasus data referensi tidak ditemukan', function () {
        $result = $this->service->calculate(20.0, 100.0, 60, 'M');

        expect($result['z_score'])->toBeNull()
            ->and($result['status'])->toBe('Tidak Dapat Dihitung');
    });
});

describe('deterministic property (sifat deterministik)', function () {
    it('menghasilkan output yang sama untuk input yang sama (property test)', function () {
        // Run 100 iterations with various inputs
        for ($i = 0; $i < 100; $i++) {
            $weight = fake()->randomFloat(1, 5.0, 25.0);
            $age = fake()->numberBetween(0, 59);
            $gender = fake()->randomElement(['M', 'F', 'L', 'P']);

            // Calculate twice with same input
            $result1 = $this->service->calculate($weight, 75.0, $age, $gender);
            $result2 = $this->service->calculate($weight, 75.0, $age, $gender);

            // Property: deterministic — same input always produces same output
            expect($result1['z_score'])->toBe($result2['z_score'])
                ->and($result1['status'])->toBe($result2['status']);
        }
    });

    it('mengklasifikasikan z-score secara konsisten (property test)', function () {
        // Run 100 iterations with random z-scores
        for ($i = 0; $i < 100; $i++) {
            $zScore = fake()->randomFloat(2, -5.0, 5.0);

            $result1 = $this->service->classifyNutritionStatus($zScore);
            $result2 = $this->service->classifyNutritionStatus($zScore);

            // Property: deterministic classification
            expect($result1)->toBe($result2);
        }
    });
});

describe('edge cases', function () {
    it('menangani berat badan sangat rendah', function () {
        $zScore = $this->service->calculateZScore(2.0, 0, 'M');

        expect($zScore)->not->toBeNull()
            ->and($zScore)->toBeLessThan(-2);
    });

    it('menangani berat badan sangat tinggi', function () {
        $zScore = $this->service->calculateZScore(5.0, 0, 'M');

        expect($zScore)->not->toBeNull()
            ->and($zScore)->toBeGreaterThan(2);
    });

    it('menangani usia 0 bulan (edge case minimal)', function () {
        $result = $this->service->calculate(3.3, 50.0, 0, 'M');

        expect($result['z_score'])->not->toBeNull()
            ->and($result['status'])->not->toBe('Tidak Dapat Dihitung');
    });

    it('menangani usia 59 bulan (edge case maksimal)', function () {
        $result = $this->service->calculate(18.3, 110.0, 59, 'M');

        expect($result['z_score'])->not->toBeNull()
            ->and($result['status'])->not->toBe('Tidak Dapat Dihitung');
    });

    it('menangani usia negatif dengan mengembalikan null', function () {
        $zScore = $this->service->calculateZScore(10.0, -1, 'M');

        expect($zScore)->toBeNull();
    });

    it('menangani usia di atas 59 bulan dengan mengembalikan null', function () {
        $zScore = $this->service->calculateZScore(20.0, 60, 'M');

        expect($zScore)->toBeNull();
    });

    it('menangani gender dengan huruf kecil', function () {
        $zScore = $this->service->calculateZScore(10.2, 12, 'l');

        expect($zScore)->not->toBeNull();
    });

    it('menangani gender dengan spasi', function () {
        $zScore = $this->service->calculateZScore(10.2, 12, ' M ');

        expect($zScore)->not->toBeNull();
    });
});
