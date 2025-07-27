<?php

namespace App\Http\Livewire\Admin\Management;

use Livewire\Component;
use App\Models\Article;

class ArticleManagement extends Component
{
    public $articles, $searchTerm;

    protected $rules = [
        'searchTerm' => 'nullable|string|min:3',
    ];

    public function mount()
    {
        $this->articles = Article::all();
    }

    public function searchArticles()
    {
        $this->validate();

        $this->articles = Article::where('title', 'like', '%' . $this->searchTerm . '%')->get();
    }

    public function render()
    {
        return view('livewire.admin.management.article-management');
    }
}
