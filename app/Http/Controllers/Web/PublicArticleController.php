<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PublicArticleController extends Controller
{
    public function index(Request $request)
    {
        $version = Cache::rememberForever('public_articles_cache_version', fn() => time());
        $page = $request->get('page', 1);
        $category = $request->get('category');
        $search = $request->get('search');
        $cacheKey = "public_articles_v{$version}_page_{$page}_cat_{$category}_search_{$search}";

        $featured = Cache::remember('featured_article', 300, function () {
            return Article::with(['category', 'user'])
                ->where('status', 'published')
                ->latest('published_at')
                ->first();
        });

        $articles = Cache::remember($cacheKey, 300, function () use ($request) {
            return Article::with(['category', 'user'])
                ->where('status', 'published')
                ->filter([
                    'category' => $request->category,
                    'search' => $request->search,
                ])
                ->latest('published_at')
                ->paginate(4)
                ->withQueryString();
        });

        $categories = Cache::remember('public_categories_count', 300, function () {
            return Category::withCount(['articles' => fn ($q) => $q->where('status', 'published')])->get();
        });

        $popularArticles = Cache::remember('popular_articles', 300, function () {
            return Article::with(['category', 'user'])
                ->where('status', 'published')
                ->latest('published_at')
                ->skip(1)
                ->take(3)
                ->get();
        });

        return view('public.articles.index', compact('articles', 'featured', 'categories', 'popularArticles'));
    }

    public function show($slug)
    {
        $article = Cache::remember("article_show_{$slug}", 300, function () use ($slug) {
            return Article::with(['category', 'user'])
                ->where('slug', $slug)
                ->where('status', 'published')
                ->firstOrFail();
        });

        return view('public.articles.show', compact('article'));
    }

    public function about()
    {
        return view('public.about.about');
    }

    public function contact()
    {
        return view('public.contact');
    }
}
