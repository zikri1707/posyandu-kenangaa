<?php

namespace App\Livewire\Admin\Management;

use App\Livewire\Shared\BaseAdminComponent;
use App\Models\Article;
use App\Models\Category;
use App\Services\ArticleService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('layouts.admin-layout')]
class ArticleCreate extends BaseAdminComponent
{
    use WithFileUploads;

    public string $title      = '';
    public string $content    = '';   // JSON string dari Alpine editor
    public string $status     = 'draft';
    public ?int   $category_id = null;
    public mixed  $cover = null;

    protected function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'content'     => 'required|string|min:2',
            'status'      => 'required|in:draft,published',
            'category_id' => 'required|exists:categories,id',
            'cover'       => 'required|image|max:4096',
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required'       => 'Judul artikel wajib diisi.',
            'content.required'     => 'Isi artikel tidak boleh kosong.',
            'category_id.required' => 'Kategori artikel wajib dipilih.',
            'cover.required'       => 'Foto sampul wajib diunggah.',
        ];
    }

    public function saveFromAlpine(string $title, string $content, string $status, int $categoryId)
    {
        $this->title       = $title;
        $this->content     = $content;
        $this->status      = $status;
        $this->category_id = $categoryId;

        $this->authorize('create', Article::class);
        
        $validated = $this->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string|min:2',
            'status'      => 'required|in:draft,published',
            'category_id' => 'required|exists:categories,id',
            'cover'       => 'required|image|max:4096',
        ]);

        $validated['thumbnail'] = $validated['cover'];
        unset($validated['cover']);

        // Inject service via app() karena Livewire v3 tidak support DI di method Alpine
        app(ArticleService::class)->createArticle($validated, Auth::user());
        
        $this->notify('Artikel berhasil disimpan.', 'success', true);
        return redirect()->route('admin.articles.index');
    }

    public function save(ArticleService $service)
    {
        $this->authorize('create', Article::class);
        $validated = $this->validate();

        // Rename cover → thumbnail untuk ArticleService
        $validated['thumbnail'] = $validated['cover'];
        unset($validated['cover']);

        $service->createArticle($validated, Auth::user());

        $this->notify('Artikel berhasil disimpan.', 'success', true);

        return redirect()->route('admin.articles.index');
    }

    public function render(): View
    {
        return view('livewire.admin.article-management.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}