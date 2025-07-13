<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controller as BaseController;

class ArticleController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin'])->except(['index', 'show']);
    }

    public function index()
    {
        $articles = Article::published()->latest()->get();
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.article-management.create');
    }

    public function store(ArticleRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()?->id;
        $data['slug'] = Str::slug($data['title']);
        
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('articles');
        }

        Article::create($data);
        return redirect()->route('articles.index')
            ->with('success', 'Article created successfully');
    }

    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        return view('admin.article-management.edit', compact('article'));
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['title']);
        
        if ($request->hasFile('thumbnail')) {
            Storage::delete($article->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('articles');
        }

        $article->update($data);
        return redirect()->route('articles.index')
            ->with('success', 'Article updated successfully');
    }

    public function destroy(Article $article)
    {
        if ($article->thumbnail) {
            Storage::delete($article->thumbnail);
        }
        
        $article->delete();
        return back()->with('success', 'Article deleted successfully');
    }

    public function publish(Article $article)
    {
        $article->update([
            'status' => 'published',
            'published_at' => now()
        ]);
        return back()->with('success', 'Article published successfully');
    }

    public function draft(Article $article)
    {
        $article->update(['status' => 'draft']);
        return back()->with('success', 'Article saved as draft');
    }
}