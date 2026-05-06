<?php

namespace App\Livewire\Admin\Management;

use App\Livewire\Shared\BaseAdminComponent;
use App\Models\Article;
use App\Models\Category;
use App\Services\ArticleService;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;

/**
 * Komponen untuk membuat artikel baru (OOP & Clean Code).
 */
#[Layout('layouts.admin-layout')]
class ArticleCreate extends BaseAdminComponent
{
    use WithFileUploads;

    public string $title = '';

    public string $content = '';

    #[Url]
    public string $status = 'draft';

    public ?int $category_id = null;

    public $thumbnail;

    /**
     * Inisialisasi komponen.
     */
    public function mount(): void
    {
        // status is auto-captured by #[Url]
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
            'thumbnail' => 'nullable|image|max:2048', // Max 2MB
        ];
    }

    /**
     * Simpan artikel baru.
     */
    public function save(ArticleService $service)
    {
        $this->authorize('create', Article::class);
        $validated = $this->validate();

        $service->createArticle($validated, auth()->user());

        $message = $this->status === 'published'
            ? 'Artikel baru berhasil diterbitkan.'
            : 'Artikel berhasil disimpan sebagai draf.';

        $this->notify($message);

        return redirect()->route('admin.articles.index');
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        return view('livewire.admin.article-management.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}
