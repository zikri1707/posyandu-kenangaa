<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Service untuk mengelola logika bisnis Artikel.
 * Menerapkan prinsip SOLID dan Clean Code.
 */
class ArticleService
{
    /**
     * Membuat artikel baru.
     */
    public function createArticle(array $data, User $author): Article
    {
        return DB::transaction(function () use ($data, $author) {
            $data['user_id'] = $author->id;
            $data['slug'] = Str::slug($data['title']);

            // Set published_at if status is published
            if (isset($data['status']) && $data['status'] === 'published') {
                $data['published_at'] = now();
            }

            // Handle thumbnail upload if exists
            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                $data['thumbnail'] = $data['thumbnail']->store('articles', 'public');
            }

            return Article::create($data);
        });
    }

    /**
     * Memperbarui artikel yang ada.
     */
    public function updateArticle(Article $article, array $data): Article
    {
        return DB::transaction(function () use ($article, $data) {
            if (isset($data['title'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            // Set published_at if status changes to published and it wasn't published before
            if (isset($data['status']) && $data['status'] === 'published' && ! $article->published_at) {
                $data['published_at'] = now();
            }

            // Handle thumbnail upload
            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                // Delete old thumbnail
                if ($article->thumbnail) {
                    Storage::disk('public')->delete($article->thumbnail);
                }
                $data['thumbnail'] = $data['thumbnail']->store('articles', 'public');
            }

            $article->update($data);

            return $article;
        });
    }

    /**
     * Menghapus artikel.
     */
    public function deleteArticle(Article $article): bool
    {
        return DB::transaction(function () use ($article) {
            if ($article->thumbnail) {
                Storage::disk('public')->delete($article->thumbnail);
            }

            return $article->delete();
        });
    }

    /**
     * Menyiapkan query artikel berdasarkan filter.
     */
    public function getFilteredArticles(array $filters)
    {
        $query = Article::with(['user', 'category']);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        $sort = $filters['sort'] ?? 'latest';
        if ($sort === 'latest') {
            $query->latest();
        } else {
            $query->oldest();
        }

        return $query;
    }
}
