<?php

namespace App\Livewire\Admin\Management;

use App\Livewire\Shared\BaseAdminComponent;
use App\Models\Article;
use App\Models\Category;
use App\Services\ArticleService;
use Illuminate\View\View;
use Livewire\WithFileUploads;

class ArticleUpdate extends BaseAdminComponent
{
    use WithFileUploads;

    public Article $article;

    public string $title      = '';
    public string $content    = '';   // JSON string dari Alpine editor
    public string $status     = '';
    public ?int   $category_id = null;
    public mixed $cover = null;
    public ?string $existingCover = null;

    public function mount(Article $article): void
    {
        $this->article       = $article;
        $this->title         = $article->title;
        $this->content       = $article->content ?? '';
        $this->status        = $article->status;
        $this->category_id   = $article->category_id;
        $this->existingCover = $article->thumbnail;
    }

    protected function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'content'     => 'required|string|min:2',
            'status'      => 'required|in:published,draft',
            'category_id' => 'required|exists:categories,id',
            'cover'       => 'nullable|image|max:4096',
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required'       => 'Judul artikel wajib diisi.',
            'content.required'     => 'Isi artikel tidak boleh kosong.',
            'category_id.required' => 'Kategori artikel wajib dipilih.',
        ];
    }

    public function save(ArticleService $service)
    {
        $this->authorize('update', $this->article);
        $validated = $this->validate();

        if (isset($validated['cover']) && $validated['cover']) {
            $validated['thumbnail'] = $validated['cover'];
        }
        unset($validated['cover']);

        $service->updateArticle($this->article, $validated);

        $this->notify('Artikel berhasil diperbarui.', 'success', true);

        return redirect()->route('admin.articles.index');
    }

    public function render(): View
    {
        return view('livewire.admin.article-management.update', [
            'categories'    => Category::orderBy('name')->get(),
            'existingCover' => $this->existingCover,
        ]);
    }
}