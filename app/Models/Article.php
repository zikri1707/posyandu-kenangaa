<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'description', 'content', 'content_blocks', 'thumbnail', 'slug', 'status', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'content_blocks' => 'array',
    ];

    protected static function booted()
    {
        static::saved(function ($article) {
            \Illuminate\Support\Facades\Cache::forget('public_categories_count');
            \Illuminate\Support\Facades\Cache::forget('popular_articles');
            \Illuminate\Support\Facades\Cache::forget('featured_article');
            \Illuminate\Support\Facades\Cache::forget('article_show_' . $article->slug);
            \Illuminate\Support\Facades\Cache::forever('public_articles_cache_version', time());
        });

        static::deleted(function ($article) {
            \Illuminate\Support\Facades\Cache::forget('public_categories_count');
            \Illuminate\Support\Facades\Cache::forget('popular_articles');
            \Illuminate\Support\Facades\Cache::forget('featured_article');
            \Illuminate\Support\Facades\Cache::forget('article_show_' . $article->slug);
            \Illuminate\Support\Facades\Cache::forever('public_articles_cache_version', time());
        });
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Hitung waktu baca artikel (perkiraan membaca per menit)
     * Rata-rata 200 kata per menit untuk pembaca dalam bahasa Indonesia
     */
    public function getReadingTimeAttribute(): string
    {
        $text = \App\Services\ArticleService::getExcerpt($this->content ?? '', 99999);
        $wordCount = str_word_count($text);
        $readingTime = max(1, (int) ceil($wordCount / 200));

        return $readingTime.' menit';
    }

    /**
     * Dapatkan excerpt dari konten artikel
     */
    public function getExcerptAttribute(): string
    {
        $text = \App\Services\ArticleService::getExcerpt($this->content ?? '', 160);

        return $text ?: 'Tidak ada ringkasan tersedia.';
    }

    /**
     * Scope a query to apply standard filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($q, $search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        });

        $query->when($filters['status'] ?? false, function ($q, $status) {
            if ($status !== 'all') {
                $q->where('status', $status);
            }
        });

        $query->when($filters['category'] ?? false, function ($q, $category) {
            $q->whereHas('category', fn ($q) => $q->where('slug', $category));
        });

        $sort = $filters['sort'] ?? 'latest';
        if ($sort === 'oldest') {
            $query->oldest('published_at');
        } else {
            $query->latest('published_at');
        }

        return $query;
    }

    /**
     * Parse YouTube URL to get clean embed URL.
     */
    public static function getYoutubeEmbedUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
        if (preg_match($pattern, $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        $shortsPattern = '/youtube\.com\/shorts\/([^"&?\/ ]{11})/i';
        if (preg_match($shortsPattern, $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        return null;
    }

    /**
     * Parse Google Drive URL to get clean preview/embed URL.
     */
    public static function getGoogleDriveEmbedUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $pattern = '/\/file\/d\/([a-zA-Z0-9_-]+)/';
        if (preg_match($pattern, $url, $matches)) {
            return 'https://drive.google.com/file/d/'.$matches[1].'/preview';
        }

        return null;
    }
}
