<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@posyandu.com',
                'username' => 'superadmin',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
                'is_active' => true,
                'verified_email' => true,
                'attempt_login' => 0,
                'block_expires' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Posyandu', // Changed from 'Kader Posyandu'
                'email' => 'admin@posyandu.com',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'role' => 'admin', // Changed from 'kader'
                'is_active' => true,
                'verified_email' => true,
                'attempt_login' => 0,
                'block_expires' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Medical Officer', // Changed from 'Dokter'
                'email' => 'medical@posyandu.com',
                'username' => 'medical',
                'password' => Hash::make('password123'),
                'role' => 'medical', // Changed from 'dokter'
                'is_active' => true,
                'verified_email' => true,
                'attempt_login' => 0,
                'block_expires' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Coordinator',
                'email' => 'coordinator@posyandu.com',
                'username' => 'coordinator',
                'password' => Hash::make('password123'),
                'role' => 'coordinator',
                'is_active' => true,
                'verified_email' => true,
                'attempt_login' => 0,
                'block_expires' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff',
                'email' => 'staff@posyandu.com',
                'username' => 'staff',
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'is_active' => true,
                'verified_email' => true,
                'attempt_login' => 0,
                'block_expires' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Patient',
                'email' => 'patient@posyandu.com',
                'username' => 'patient',
                'password' => Hash::make('password123'),
                'role' => 'patient',
                'is_active' => true,
                'verified_email' => true,
                'attempt_login' => 0,
                'block_expires' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Partner',
                'email' => 'partner@posyandu.com',
                'username' => 'partner',
                'password' => Hash::make('password123'),
                'role' => 'partner',
                'is_active' => true,
                'verified_email' => true,
                'attempt_login' => 0,
                'block_expires' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}