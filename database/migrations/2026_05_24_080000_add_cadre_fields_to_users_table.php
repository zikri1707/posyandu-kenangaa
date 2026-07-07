<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('users', function (Blueprint $table) {
            $table->string('cadre_role')->nullable();
            $table->string('ttl')->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('pendidikan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('hp')->nullable();
            $table->string('image')->nullable();
        });

        // Seed 10 default cadres
        $kaders = [
            [
                'name' => 'Sri Hartati',
                'role' => 'kader',
                'cadre_role' => 'Ketua Kader',
                'ttl' => 'Lampung, 12 April 1962',
                'nik' => '3275015204620012',
                'pendidikan' => 'SLTA',
                'alamat' => 'Jl P. Sumba 8 No. 232 RT 001 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '081380473365',
                'email' => 'denyyoga2212@gmail.com',
                'image' => 'assets/img/kaders/sri_hartati.png',
            ],
            [
                'name' => 'Widayanti Christiani',
                'role' => 'kader',
                'cadre_role' => 'Sekretaris',
                'ttl' => 'Jakarta, 05 April 1982',
                'nik' => '3275014504820054',
                'pendidikan' => 'SLTA',
                'alamat' => 'Jl P. Bali 1 No. 330 RT 002 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '08139914428',
                'email' => 'widayantichristiani@yahoo.co.id',
                'image' => 'assets/img/kaders/widayanti.jpg',
            ],
            [
                'name' => 'Parniyati',
                'role' => 'kader',
                'cadre_role' => 'Bendahara',
                'ttl' => 'Karanganyar, 15 Juli 1971',
                'nik' => '3275015507710014',
                'pendidikan' => 'SLTA',
                'alamat' => 'Jl P. Madura 3 No 37 RT 004 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '085770153741',
                'email' => 'parniyati15.71@gmail.com',
                'image' => 'assets/img/kaders/parniyati.png',
            ],
            [
                'name' => 'Arimbi Kurniasari',
                'role' => 'kader',
                'cadre_role' => 'Anggota',
                'ttl' => 'Klaten, 28 November 1976',
                'nik' => '3275016811760020',
                'pendidikan' => 'Magister',
                'alamat' => 'Jl P. Madura 4 No. 15 RT 003 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '081297963177',
                'email' => 'arimbi28sari@ggmail.com',
                'image' => 'assets/img/kaders/arimbi.png',
            ],
            [
                'name' => 'Dewi Pastrinah',
                'role' => 'kader',
                'cadre_role' => 'Anggota',
                'ttl' => 'Jakarta, 29 Desember 1981',
                'nik' => '3275016912810022',
                'pendidikan' => 'SMK',
                'alamat' => 'Jl P. Madura 4 No. 22 RT 003 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '081517001791',
                'email' => 'dewigedhe81@gmail.com',
                'image' => 'assets/img/kaders/dewi_pastrinah.png',
            ],
            [
                'name' => 'Tionar Maulina Purba',
                'role' => 'kader',
                'cadre_role' => 'Anggota',
                'ttl' => 'Dolok Sanggul, 25 Januari 1959',
                'nik' => '3275016501590013',
                'pendidikan' => 'SLTA',
                'alamat' => 'Jl P. Madura 3 No 38 RT 004 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '081218385669',
                'email' => 'tionar.mp@gmail.com',
                'image' => 'assets/img/kaders/tionar.png',
            ],
            [
                'name' => 'Maita Indriati',
                'role' => 'kader',
                'cadre_role' => 'Anggota',
                'ttl' => 'Jakarta, 19 Mei 1963',
                'nik' => '3275015905630012',
                'pendidikan' => 'Sarjana',
                'alamat' => 'Jl Sumba Raya No 03 RT 001 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '081295743714',
                'email' => 'Maitaindriati1905@gmail.com',
                'image' => 'assets/img/kaders/maita.png',
            ],
            [
                'name' => 'Arfah',
                'role' => 'kader',
                'cadre_role' => 'Anggota',
                'ttl' => 'Jakarta, 15 Mei 1967',
                'nik' => '3275015505670018',
                'pendidikan' => 'SLTA',
                'alamat' => 'Jl Sumba Raya No 27 RT 002 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '087856068033',
                'email' => 'arfah.6715@gmail.com',
                'image' => 'assets/img/kaders/arfah.png',
            ],
            [
                'name' => 'Mustikasari',
                'role' => 'kader',
                'cadre_role' => 'Anggota',
                'ttl' => 'Subang, 09 September 1956',
                'nik' => '3275014909560018',
                'pendidikan' => 'SLTA',
                'alamat' => 'Jl P. Sumba 7 No. 254 RT 001 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '081293290635',
                'email' => 'Mustikasari@gmail.com',
                'image' => 'assets/img/kaders/mustikasari.png',
            ],
            [
                'name' => 'Ika Rakhmawati',
                'role' => 'kader',
                'cadre_role' => 'Anggota',
                'ttl' => 'Jakarta, 15 Agustus 1978',
                'nik' => '3275015508780053',
                'pendidikan' => 'SLTA',
                'alamat' => 'Jl P. Madura 2 No. 58 RT 004 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                'hp' => '081315662377',
                'email' => 'ika@posyandu.com',
                'image' => 'assets/img/kaders/ika.jpeg',
            ],
        ];

        // 1. Hapus constraint cukup sekali saja di luar looping (hanya untuk non-sqlite)
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
        }

        foreach ($kaders as $k) {
            $username = strtolower(explode(' ', $k['name'])[0]).'_'.rand(100, 999);
            
            // Check if email already exists
            if (! DB::table('users')->where('email', $k['email'])->exists()) {
                DB::table('users')->insert([
                    'name' => $k['name'],
                    'email' => $k['email'],
                    'username' => $username,
                    'password' => Hash::make('password123'),
                    'role' => $k['role'],
                    'cadre_role' => $k['cadre_role'],
                    'ttl' => $k['ttl'],
                    'nik' => $k['nik'],
                    'pendidikan' => $k['pendidikan'],
                    'alamat' => $k['alamat'],
                    'hp' => $k['hp'],
                    'image' => $k['image'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cadre_role', 'ttl', 'nik', 'pendidikan', 'alamat', 'hp', 'image']);
        });
    }
};
