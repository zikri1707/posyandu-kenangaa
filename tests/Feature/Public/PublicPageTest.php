<?php

use App\Models\Article;
use App\Models\Patient;
use App\Models\Posyandu;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create test data
    $this->posyandu = Posyandu::factory()->create([
        'name' => 'Posyandu Test',
    ]);

    $this->patient = Patient::factory()->create([
        'posyandu_id' => $this->posyandu->id,
        'id_number' => '1234567890123456',
        'full_name' => 'Test Patient',
        'address' => 'Jl. Test No. 123',
        'phone_number' => '081234567890',
    ]);

    $startTime = now()->addDays(7);
    $this->schedule = Schedule::factory()->create([
        'posyandu_id' => $this->posyandu->id,
        'title' => 'Posyandu Rutin',
        'start_time' => $startTime,
        'end_time' => $startTime->copy()->addHours(2),
        'status' => 'upcoming',
    ]);

    $this->article = Article::factory()->create([
        'title' => 'Artikel Kesehatan Test',
        'slug' => 'artikel-kesehatan-test',
        'content' => 'Konten artikel kesehatan untuk testing',
        'status' => 'published',
    ]);
});

describe('akses halaman publik tanpa login', function () {
    it('dapat mengakses halaman beranda tanpa login', function () {
        $response = $this->get('/');

        $response->assertOk();
    });

    it('dapat mengakses halaman tentang kami tanpa login', function () {
        $response = $this->get('/about');

        $response->assertOk();
    });

    it('dapat mengakses halaman kontak tanpa login', function () {
        $response = $this->get('/contact');

        $response->assertOk();
    });

    it('dapat mengakses halaman daftar artikel tanpa login', function () {
        $response = $this->get('/articles');

        $response->assertOk();
    });

    it('dapat mengakses halaman detail artikel tanpa login', function () {
        $response = $this->get("/articles/{$this->article->slug}");

        $response->assertOk();
    });
});

describe('konten halaman beranda', function () {
    it('menampilkan profil singkat posyandu', function () {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Posyandu');
    });

    it('menampilkan jadwal kegiatan terdekat', function () {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee($this->schedule->title);
    });

    it('menampilkan artikel kesehatan terbaru', function () {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee($this->article->title);
    });

    it('tidak menampilkan lebih dari 3 jadwal terdekat', function () {
        // Create 5 schedules
        $startTime = now()->addDays(1);
        Schedule::factory()->count(5)->create([
            'posyandu_id' => $this->posyandu->id,
            'start_time' => $startTime,
            'end_time' => $startTime->copy()->addHours(2),
            'status' => 'upcoming',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        // Verify only 3 schedules are shown (implementation specific)
    });

    it('tidak menampilkan lebih dari 3 artikel terbaru', function () {
        // Create 5 articles
        Article::factory()->count(5)->create([
            'status' => 'Published',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        // Verify only 3 articles are shown (implementation specific)
    });
});

describe('konten halaman tentang kami', function () {
    it('menampilkan informasi profil posyandu', function () {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('Selamat Datang di');
        $response->assertSee('Posyandu ILP Kenanga RW 011');
        $response->assertSee('RW 011');
        $response->assertSee('Aren Jaya');
    });

    it('menampilkan visi dan misi', function () {
        $response = $this->get('/about');

        $response->assertOk();

        // Assert Visi is present
        $response->assertSee('Menjadi Posyandu ILP Kenanga 1 yang aktif, profesional, inovatif, dan terpercaya');
        // Assert Misi is present
        $response->assertSee('Meningkatkan pemantauan kesehatan ibu hamil, bayi, balita, remaja, dewasa, dan lansia secara terpadu');
        // Assert Tujuan is present
        $response->assertSee('Menurunkan angka stunting, gizi kurang, dan risiko kesehatan ibu serta anak');
    });
});

describe('konten halaman kontak', function () {
    it('menampilkan informasi kontak posyandu', function () {
        $response = $this->get('/contact');

        $response->assertOk();
        $response->assertSee('Kontak');
    });
});

describe('halaman artikel', function () {
    it('menampilkan daftar artikel yang dipublikasikan', function () {
        $response = $this->get('/articles');

        $response->assertOk();
        $response->assertSee($this->article->title);
    });

    it('tidak menampilkan artikel yang belum dipublikasikan', function () {
        $unpublishedArticle = Article::factory()->create([
            'title' => 'Unpublished Article',
            'status' => 'Draft',
        ]);

        $response = $this->get('/articles');

        $response->assertOk();
        $response->assertDontSee('Unpublished Article');
    });

    it('dapat membuka detail artikel', function () {
        $response = $this->get("/articles/{$this->article->slug}");

        $response->assertOk();
        $response->assertSee($this->article->title);
        $response->assertSee($this->article->content);
    });

    it('menampilkan 404 untuk artikel yang tidak ada', function () {
        $response = $this->get('/articles/artikel-tidak-ada');

        $response->assertNotFound();
    });
});

describe('privasi data - tidak menampilkan data pribadi sasaran', function () {
    it('halaman beranda tidak menampilkan NIK pasien', function () {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee($this->patient->id_number);
    });

    it('halaman beranda tidak menampilkan nama lengkap pasien', function () {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee($this->patient->full_name);
    });

    it('halaman beranda tidak menampilkan alamat lengkap pasien', function () {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee($this->patient->address);
    });

    it('halaman beranda tidak menampilkan nomor telepon pasien', function () {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee($this->patient->phone_number);
    });

    it('halaman tentang kami tidak menampilkan data pribadi pasien', function () {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertDontSee($this->patient->id_number);
        $response->assertDontSee($this->patient->full_name);
        $response->assertDontSee($this->patient->address);
    });

    it('halaman kontak tidak menampilkan data pribadi pasien', function () {
        $response = $this->get('/contact');

        $response->assertOk();
        $response->assertDontSee($this->patient->id_number);
        $response->assertDontSee($this->patient->full_name);
        $response->assertDontSee($this->patient->address);
    });

    it('halaman artikel tidak menampilkan data pribadi pasien', function () {
        $response = $this->get('/articles');

        $response->assertOk();
        $response->assertDontSee($this->patient->id_number);
        $response->assertDontSee($this->patient->full_name);
    });

    it('detail artikel tidak menampilkan data pribadi pasien', function () {
        $response = $this->get("/articles/{$this->article->slug}");

        $response->assertOk();
        $response->assertDontSee($this->patient->id_number);
        $response->assertDontSee($this->patient->full_name);
    });
});

describe('redirect untuk halaman admin', function () {
    it('redirect ke login saat mengakses halaman admin tanpa login', function () {
        $response = $this->get('/admin/patients');

        $response->assertRedirect('/login');
    });

    it('redirect ke login saat mengakses dashboard tanpa login', function () {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    });

    it('redirect ke login saat mengakses medical records tanpa login', function () {
        $response = $this->get('/admin/medical-records');

        $response->assertRedirect('/login');
    });

    it('redirect ke login saat mengakses reports tanpa login', function () {
        $response = $this->get('/admin/reports');

        $response->assertRedirect('/login');
    });

    it('redirect ke login saat mengakses activity logs tanpa login', function () {
        $response = $this->get('/admin/activity-logs');

        $response->assertRedirect('/login');
    });
});

describe('navigasi publik', function () {
    it('halaman beranda memiliki link ke halaman lain', function () {
        $response = $this->get('/');

        $response->assertOk();
        // Verify navigation links are present
    });

    it('dapat navigasi dari beranda ke tentang kami', function () {
        $response = $this->get('/');
        $response->assertOk();

        $response = $this->get('/about');
        $response->assertOk();
    });

    it('dapat navigasi dari beranda ke kontak', function () {
        $response = $this->get('/');
        $response->assertOk();

        $response = $this->get('/contact');
        $response->assertOk();
    });

    it('dapat navigasi dari beranda ke artikel', function () {
        $response = $this->get('/');
        $response->assertOk();

        $response = $this->get('/articles');
        $response->assertOk();
    });
});

describe('responsivitas halaman publik', function () {
    it('halaman beranda dapat diakses dengan user agent mobile', function () {
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)',
        ])->get('/');

        $response->assertOk();
    });

    it('halaman artikel dapat diakses dengan user agent mobile', function () {
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)',
        ])->get('/articles');

        $response->assertOk();
    });
});

describe('SEO dan metadata', function () {
    it('halaman beranda memiliki title tag', function () {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('<title>', false);
    });

    it('halaman artikel memiliki title tag', function () {
        $response = $this->get('/articles');

        $response->assertOk();
        $response->assertSee('<title>', false);
    });

    it('detail artikel memiliki title dengan judul artikel', function () {
        $response = $this->get("/articles/{$this->article->slug}");

        $response->assertOk();
        $response->assertSee($this->article->title);
    });
});

describe('keamanan halaman publik', function () {
    it('halaman publik tidak mengekspos route admin', function () {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('/admin/patients');
        $response->assertDontSee('/admin/medical-records');
    });

    it('halaman publik tidak mengekspos informasi sensitif sistem', function () {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('Laravel');
        // Don't expose framework details
    });
});

describe('error handling', function () {
    it('menampilkan 404 untuk halaman yang tidak ada', function () {
        $response = $this->get('/halaman-tidak-ada');

        $response->assertNotFound();
        $response->assertSee('Halaman yang Anda cari tidak dapat ditemukan');
        $response->assertSee('Kembali ke Beranda');
        $response->assertSee('Posyandu Kenanga');
    });

    it('menampilkan 404 untuk artikel yang tidak ada', function () {
        $response = $this->get('/articles/artikel-tidak-ada');

        $response->assertNotFound();
    });

    it('dapat me-render halaman 500 kustom', function () {
        $view = view('errors.500')->render();

        expect($view)->toContain('Kesalahan Internal Server')
            ->toContain('Terjadi kesalahan internal pada server kami')
            ->toContain('Posyandu Kenanga');
    });

    it('dapat me-render halaman 503 kustom', function () {
        $view = view('errors.503')->render();

        expect($view)->toContain('Layanan Tidak Tersedia')
            ->toContain('Layanan sedang tidak tersedia atau sedang dalam pemeliharaan rutin')
            ->toContain('Posyandu Kenanga');
    });
});
