<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        $sort = $request->get('sort', 'latest');

        $articles = Article::with(['user', 'category'])
            ->filter([
                'search' => $search,
                'status' => $status,
                'sort' => $sort,
            ])
            ->paginate(10)
            ->withQueryString();

        return view('livewire.admin.article-management.index', compact('articles', 'search', 'status', 'sort'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();

        return view('livewire.admin.article-management.create', compact('categories'));
    }

    public function store(ArticleRequest $request, \App\Services\ArticleService $articleService)
    {
        $articleService->createArticle($request->validated(), auth()->id());

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dibuat.');
    }

    public function show(Article $article)
    {
        return view('livewire.admin.article-management.details', compact('article'));
    }

    public function edit(Article $article)
    {
        $categories = \App\Models\Category::all();

        return view('livewire.admin.article-management.update', compact('article', 'categories'));
    }

    public function update(ArticleRequest $request, Article $article, \App\Services\ArticleService $articleService)
    {
        $articleService->updateArticle($article, $request->validated());

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article, \App\Services\ArticleService $articleService)
    {
        $articleService->deleteArticle($article);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
