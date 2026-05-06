<?php

namespace App\Livewire\Admin\Management;

use App\Livewire\Shared\BaseAdminComponent;
use App\Models\Article;
use Illuminate\View\View;

/**
 * Komponen untuk melihat detail artikel (OOP & Clean Code).
 */
class ArticleShow extends BaseAdminComponent
{
    public Article $article;

    /**
     * Inisialisasi data.
     */
    public function mount(Article $article): void
    {
        $this->article = $article->load(['user', 'category']);
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        return view('livewire.admin.article-management.details');
    }
}
