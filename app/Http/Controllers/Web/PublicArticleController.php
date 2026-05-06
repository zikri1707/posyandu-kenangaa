<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['category', 'user'])
            ->where('status', 'published')
            ->filter([
                'category' => $request->category,
                'search' => $request->search,
            ])
            ->latest('published_at');

        $featured = Article::with(['category', 'user'])
            ->where('status', 'published')
            ->latest('published_at')
            ->first();

        $articles = $query->paginate(4)->withQueryString();
        $categories = Category::withCount(['articles' => fn ($q) => $q->where('status', 'published')])->get();

        $popularArticles = Article::with(['category'])
            ->where('status', 'published')
            ->latest('published_at')
            ->skip(1)
            ->take(3)
            ->get();

        return view('public.articles.index', compact('articles', 'featured', 'categories', 'popularArticles'));
    }

    public function show($slug)
    {
        $article = Article::with(['category', 'user'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('public.articles.show', compact('article'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        return view('public.contact');
    }
}
