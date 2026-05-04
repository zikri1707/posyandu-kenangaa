<?php

namespace App\Livewire\Admin\Management;

use App\Models\Article;
use App\Services\ArticleService;
use App\Livewire\Shared\BaseAdminComponent;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Illuminate\View\View;

/**
 * Komponen Manajemen Artikel (OOP & Clean Code).
 * Mengelola daftar artikel, pencarian, dan penghapusan.
 */
#[Layout('layouts.admin-layout')]
class ArticleManagement extends BaseAdminComponent
{
    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $status = '';

    #[Url(except: 'latest')]
    public string $sort = 'latest';

    protected $queryString = [];

    /**
     * Render halaman manajemen artikel.
     */
    public function render(ArticleService $service): View
    {
        $filters = [
            'search' => $this->search,
            'status' => $this->status,
            'sort'   => $this->sort,
        ];

        return view('livewire.admin.article-management.index', [
            'articles' => $service->getFilteredArticles($filters)->paginate(10),
        ]);
    }

    /**
     * Hapus artikel dengan autorisasi.
     */
    public function deleteArticle(int $id, ArticleService $service): void
    {
        $article = Article::findOrFail($id);
        $this->authorize('delete', $article);
        
        $service->deleteArticle($article);
        $this->notify('Artikel berhasil dihapus permanen.');
    }
}
