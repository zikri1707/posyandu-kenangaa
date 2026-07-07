<?php

namespace App\Services;

use App\DataTransferObjects\CadreData;
use App\DataTransferObjects\GoalData;
use App\DataTransferObjects\MissionData;

class AboutPageService
{
    /**
     * Get missions data for the about page.
     *
     * @return array<MissionData>
     */
    public function getMissions(): array
    {
        $misis = [
            [
                'icon' => 'medical_services',
                'title' => 'Layanan Kesehatan Dasar',
                'desc' => 'Menyelenggarakan pelayanan kesehatan dasar yang mudah diakses, ramah, dan berkualitas bagi seluruh siklus kehidupan masyarakat.',
            ],
            [
                'icon' => 'family_history',
                'title' => 'Pemantauan Terpadu',
                'desc' => 'Meningkatkan pemantauan kesehatan ibu hamil, bayi, balita, remaja, dewasa, dan lansia secara terpadu.',
            ],
            [
                'icon' => 'nutrition',
                'title' => 'Pencegahan Risiko stunting',
                'desc' => 'Mendukung upaya pencegahan stunting, gizi buruk, serta penyakit menular dan tidak menular melalui edukasi dan deteksi dini.',
            ],
            [
                'icon' => 'groups',
                'title' => 'Pemberdayaan & Kolaborasi',
                'desc' => 'Meningkatkan kapasitas kader Posyandu agar kompeten dan sigap, serta menjalin kerja sama erat dengan Puskesmas Bekasi Timur.',
            ],
        ];

        return array_map(fn ($m) => MissionData::fromArray($m), $misis);
    }

    /**
     * Get goals data for the about page.
     *
     * @return array<GoalData>
     */
    public function getGoals(): array
    {
        $tujuans = [
            [
                'icon' => 'volunteer_activism',
                'title' => 'Derajat Kesehatan Utama',
                'desc' => 'Meningkatkan derajat kesehatan masyarakat di lingkungan Posyandu ILP Kenanga 1.',
            ],
            [
                'icon' => 'child_care',
                'title' => 'Pemberantasan Stunting',
                'desc' => 'Menurunkan angka stunting, gizi kurang, dan risiko kesehatan ibu serta anak.',
            ],
            [
                'icon' => 'vaccines',
                'title' => 'Cakupan Imunisasi Lengkap',
                'desc' => 'Meningkatkan cakupan imunisasi, pemantauan tumbuh kembang, dan pemeriksaan kesehatan rutin.',
            ],
            [
                'icon' => 'health_and_safety',
                'title' => 'Kesadaran Hidup Bersih (PHBS)',
                'desc' => 'Meningkatkan kesadaran masyarakat terhadap pentingnya pola hidup sehat and pencegahan penyakit.',
            ],
            [
                'icon' => 'sync_alt',
                'title' => 'Layanan Berkelanjutan',
                'desc' => 'Mewujudkan pelayanan Posyandu yang terintegrasi, berkelanjutan, dan bermanfaat bagi seluruh warga.',
            ],
            [
                'icon' => 'workspace_premium',
                'title' => 'Portal Layanan Terpercaya',
                'desc' => 'Menjadikan Posyandu ILP Kenanga 1 sebagai pusat layanan kesehatan masyarakat yang nyaman dan terpercaya.',
            ],
        ];

        return array_map(fn ($t) => GoalData::fromArray($t), $tujuans);
    }

    /**
     * Get cadres data for the about page.
     *
     * @return array<CadreData>
     */
    public function getCadres(): array
    {
        $kaders = \Illuminate\Support\Facades\Cache::remember('about_page_kaders', 600, function () {
            $users = \App\Models\User::whereIn('role', ['admin', 'kader'])
                ->where('is_active', true)
                ->orderBy('id')
                ->get();

            return $users->map(function ($user) {
                $imagePath = $user->image;
                if (empty($imagePath)) {
                    $imagePath = asset('assets/img/kaders/placeholder.svg'); // placeholder default jika kosong
                } else {
                    $webpName = pathinfo($imagePath, PATHINFO_FILENAME) . '.webp';
                    
                    if (str_starts_with($imagePath, 'assets/')) {
                        $localPath = public_path($imagePath);
                        $localWebpPath = dirname($localPath) . '/' . $webpName;
                        
                        if (file_exists($localWebpPath)) {
                            $imagePath = dirname($imagePath) . '/' . $webpName;
                        }
                        $imagePath = asset($imagePath);
                    } else {
                        $localWebpPath = public_path('assets/img/kaders/' . $webpName);
                        if (file_exists($localWebpPath)) {
                            $imagePath = asset('assets/img/kaders/' . $webpName);
                        } else {
                            $imagePath = \Illuminate\Support\Facades\Storage::url('kaders/'.$imagePath);
                        }
                    }
                }

                return [
                    'name' => $user->name,
                    'role' => $user->cadre_role ?? 'Kader',
                    'ttl' => $user->ttl ?? '-',
                    'nik' => $user->nik ?? '-',
                    'pendidikan' => $user->pendidikan ?? '-',
                    'alamat' => $user->alamat ?? '-',
                    'hp' => $user->hp ?? '-',
                    'email' => $user->email,
                    'image' => $imagePath,
                ];
            })->toArray();
        });

        return array_map(fn ($k) => CadreData::fromArray($k), $kaders);
    }

    /**
     * Get total count of target citizens (warga sasaran).
     *
     * @return int
     */
    public function getSasaranCount(): int
    {
        return \App\Models\Patient::count();
    }

    /**
     * Get total count of Posyandu units.
     *
     * @return int
     */
    public function getPosyanduCount(): int
    {
        return \App\Models\Posyandu::count();
    }
}
