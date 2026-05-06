<?php

namespace App\Livewire\Admin\Management;

use App\Livewire\Shared\BaseAdminComponent;
use App\Models\Article;
use App\Models\Category;
use App\Services\ArticleService;
use Illuminate\View\View;
use Livewire\WithFileUploads;

/**
 * Komponen untuk memperbarui artikel (OOP & Clean Code).
 */
class ArticleUpdate extends BaseAdminComponent
{
    use WithFileUploads;

    public Article $article;

    public string $title = '';

    public string $content = '';

    public string $status = '';

    public ?int $category_id = null;

    public $thumbnail;

    public $oldThumbnail;

    /**
     * Inisialisasi data.
     */
    public function mount(Article $article): void
    {
        $this->article = $article;
        $this->title = $article->title;
        $this->content = $article->content;
        $this->status = $article->status;
        $this->category_id = $article->category_id;
        $this->oldThumbnail = $article->thumbnail;
    }

    /**
     * Aturan validasi.
     */
    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:published,draft',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|max:2048',
        ];
    }

    /**
     * Simpan perubahan artikel.
     */
    public function save(ArticleService $service)
    {
        $this->authorize('update', $this->article);
        $validated = $this->validate();

        $service->updateArticle($this->article, $validated);

        $this->notify('Artikel berhasil diperbarui.');

        return redirect()->route('admin.articles.index');
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        return view('livewire.admin.article-management.update', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}
