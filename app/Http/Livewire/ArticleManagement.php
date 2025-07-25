<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleManagement extends Component
{
    use WithPagination;

    public $search = '';

    protected $rules = [
        'search' => 'nullable|string|min:3',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $articles = Article::where('title', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.article-management', compact('articles'));
    }
}
