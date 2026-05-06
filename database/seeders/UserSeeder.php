<?php

namespace Database\Seeders;

use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Superadmins (no posyandu scope) ──────────────────────────────────
        $superadmins = [
            [
                'name' => 'Sekretaris Posyandu',
                'email' => 'sekretaris@posyandu.com',
                'username' => 'sekretaris',
                'role' => User::ROLE_SUPERADMIN,
                'posyandu_id' => null,
            ],
            [
                'name' => 'Ibu Arimbi',
                'email' => 'arimbi@posyandu.com',
                'username' => 'arimbi',
                'role' => User::ROLE_SUPERADMIN,
                'posyandu_id' => null,
            ],
        ];

        foreach ($superadmins as $data) {
            User::updateOrCreate(
                ['username' => $data['username']],
                array_merge($data, [
                    'password' => Hash::make('password123'),
                    'is_active' => true,
                    'verified_email' => true,
                    'attempt_login' => 0,
                    'block_expires' => null,
                    'email_verified_at' => now(),
                ])
            );
        }

        // ── Coordinator (cross-posyandu oversight) ────────────────────────────
        User::updateOrCreate(
            ['username' => 'koordinator'],
            [
                'name' => 'Koordinator Wilayah',
                'email' => 'koordinator@posyandu.com',
                'username' => 'koordinator',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_COORDINATOR,
                'posyandu_id' => null,
                'is_active' => true,
                'verified_email' => true,
                'attempt_login' => 0,
                'block_expires' => null,
                'email_verified_at' => now(),
            ]
        );

        // ── Per-posyandu users ────────────────────────────────────────────────
        $posyanduUsers = [
            // Posyandu Melati
            [
                'posyandu_code' => 'PSY001',
                'name' => 'Admin Melati',
                'email' => 'admin.melati@posyandu.com',
                'username' => 'admin_melati',
                'role' => User::ROLE_ADMIN,
            ],
            [
                'posyandu_code' => 'PSY001',
                'name' => 'Kader Melati',
                'email' => 'kader.melati@posyandu.com',
                'username' => 'kader_melati',
                'role' => User::ROLE_KADER,
            ],
            // Posyandu Mawar
            [
                'posyandu_code' => 'PSY002',
                'name' => 'Admin Mawar',
                'email' => 'admin.mawar@posyandu.com',
                'username' => 'admin_mawar',
                'role' => User::ROLE_ADMIN,
            ],
            [
                'posyandu_code' => 'PSY002',
                'name' => 'Kader Mawar',
                'email' => 'kader.mawar@posyandu.com',
                'username' => 'kader_mawar',
                'role' => User::ROLE_KADER,
            ],
            // Posyandu Anggrek
            [
                'posyandu_code' => 'PSY003',
                'name' => 'Admin Anggrek',
                'email' => 'admin.anggrek@posyandu.com',
                'username' => 'admin_anggrek',
                'role' => User::ROLE_ADMIN,
            ],
            [
                'posyandu_code' => 'PSY003',
                'name' => 'Kader Anggrek',
                'email' => 'kader.anggrek@posyandu.com',
                'username' => 'kader_anggrek',
                'role' => User::ROLE_KADER,
            ],
            // KENANGA 1
            [
                'posyandu_code' => 'KENANGA1',
                'name' => 'Admin Kenanga 1',
                'email' => 'admin.kenanga1@posyandu.com',
                'username' => 'admin_kenanga1',
                'role' => User::ROLE_ADMIN,
            ],
            [
                'posyandu_code' => 'KENANGA1',
                'name' => 'Moderator Kenanga 1',
                'email' => 'kenanga1@posyandu.com',
                'username' => 'kenanga1',
                'role' => User::ROLE_KADER,
            ],
        ];

        foreach ($posyanduUsers as $data) {
            $posyandu = Posyandu::where('unique_code', $data['posyandu_code'])->firstOrFail();

            User::updateOrCreate(
                ['username' => $data['username']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'username' => $data['username'],
                    'password' => Hash::make('password123'),
                    'role' => $data['role'],
                    'posyandu_id' => $posyandu->id,
                    'is_active' => true,
                    'verified_email' => true,
                    'attempt_login' => 0,
                    'block_expires' => null,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
