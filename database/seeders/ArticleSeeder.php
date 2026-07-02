<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first() ?? User::factory()->create([
            'role' => 'superadmin',
            'full_name' => 'Bidan Sari',
            'email' => 'admin@posyandu.com',
        ]);

        $categories = [
            ['name' => 'Kesehatan Ibu', 'slug' => 'kesehatan-ibu'],
            ['name' => 'Nutrisi & Gizi', 'slug' => 'nutrisi-gizi'],
            ['name' => 'Tumbuh Kembang', 'slug' => 'tumbuh-kembang'],
            ['name' => 'Info Layanan', 'slug' => 'info-layanan'],
        ];

        foreach ($categories as $cat) {
            $category = Category::create($cat);

            if ($cat['slug'] === 'kesehatan-ibu') {
                Article::create([
                    'user_id' => $admin->id,
                    'category_id' => $category->id,
                    'title' => 'Menjaga Kesehatan Ibu dan Anak: Peran Posyandu dalam Masyarakat',
                    'content' => '<p>Pelayanan rutin Posyandu terus ditingkatkan untuk memastikan tumbuh kembang anak yang optimal dan kesehatan ibu yang terjaga. Melalui berbagai program seperti imunisasi, pemberian vitamin, dan edukasi gizi, Posyandu menjadi garda terdepan dalam menciptakan generasi yang sehat dan kuat.</p><p>Pemeriksaan rutin setiap bulan sangat penting untuk memantau perkembangan berat badan dan tinggi badan anak, serta memberikan konseling gizi kepada para ibu.</p>',
                    'thumbnail' => null, // Will use placeholder in view for now
                    'slug' => 'menjaga-kesehatan-ibu-dan-anak',
                    'status' => 'published',
                    'published_at' => now(),
                ]);
            }

            if ($cat['slug'] === 'nutrisi-gizi') {
                Article::create([
                    'user_id' => $admin->id,
                    'category_id' => $category->id,
                    'title' => 'Menu Sehat Pendamping ASI (MPASI) Terbaik',
                    'content' => '<p>Panduan gizi penting selama masa kehamilan agar ibu dan janin tetap sehat dan bertenaga sepanjang hari. MPASI yang sehat harus mengandung nutrisi makro dan mikro yang seimbang, mulai dari karbohidrat, protein hewani, hingga lemak sehat.</p>',
                    'thumbnail' => null,
                    'slug' => 'menu-sehat-mpasi-terbaik',
                    'status' => 'published',
                    'published_at' => now()->subDays(1),
                ]);
            }

            if ($cat['slug'] === 'tumbuh-kembang') {
                Article::create([
                    'user_id' => $admin->id,
                    'category_id' => $category->id,
                    'title' => 'Pentingnya Imunisasi Dasar Lengkap',
                    'content' => '<p>Imunisasi adalah cara terbaik untuk melindungi anak dari berbagai penyakit berbahaya yang dapat dicegah. Pastikan anak Anda mendapatkan jadwal imunisasi yang lengkap sesuai anjuran tenaga kesehatan di Posyandu terdekat.</p>',
                    'thumbnail' => null,
                    'slug' => 'pentingnya-imunisasi-dasar-lengkap',
                    'status' => 'published',
                    'published_at' => now()->subDays(2),
                ]);
            }
        }

        // Add more dummy articles to fill the grid
        for ($i = 0; $i < 6; $i++) {
            Article::create([
                'user_id' => $admin->id,
                'category_id' => Category::inRandomOrder()->first()->id,
                'title' => 'Tips Kesehatan Keluarga Bagian '.($i + 1),
                'content' => '<p>Ini adalah artikel edukasi kesehatan untuk memberikan wawasan lebih mendalam tentang pola hidup sehat di lingkungan keluarga.</p>',
                'thumbnail' => null,
                'slug' => 'tips-kesehatan-keluarga-'.($i + 1),
                'status' => 'published',
                'published_at' => now()->subDays($i + 3),
            ]);
        }
    }
}
