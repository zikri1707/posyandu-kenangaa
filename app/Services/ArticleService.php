<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Service untuk mengelola logika bisnis Artikel.
 * Mendukung block-based JSON content dari Medium-style editor.
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
            $data['slug']    = $this->generateUniqueSlug($data['title']);

            if (isset($data['status']) && $data['status'] === 'published') {
                $data['published_at'] = now();
            }

            // Handle cover/thumbnail upload
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
                $data['slug'] = $this->generateUniqueSlug($data['title'], $article->id);
            }

            if (isset($data['status']) && $data['status'] === 'published' && ! $article->published_at) {
                $data['published_at'] = now();
            }

            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
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
        $query->orderBy('created_at', $sort === 'oldest' ? 'asc' : 'desc');

        return $query;
    }

    /**
     * Render block-based JSON content ke HTML untuk tampilan publik.
     * Handles all block types: paragraph, h1, h2, h3, quote, callout,
     * bullet, numbered, image, video, divider.
     */
    public static function renderContent(string $content): string
    {
        if (empty(trim($content))) {
            return '<p class="text-slate-400 italic">Konten artikel belum tersedia.</p>';
        }

        // Try to parse as JSON blocks
        try {
            $blocks = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
            if (! is_array($blocks) || empty($blocks)) {
                throw new \Exception('Not a valid block array');
            }
        } catch (\Throwable $e) {
            // Fallback: treat as plain HTML/text
            return '<p class="article-paragraph">' . nl2br(e($content)) . '</p>';
        }

        $html        = '';
        $numberedSeq = 0; // running counter for numbered list items

        foreach ($blocks as $block) {
            $type    = $block['type']    ?? 'paragraph';
            $content = $block['content'] ?? '';

            // Reset numbered counter when we hit a non-numbered block
            if ($type !== 'numbered') {
                $numberedSeq = 0;
            }

            switch ($type) {

                // ── TEXT BLOCKS ──────────────────────────────────────────
                case 'paragraph':
                    // Allow inline HTML (bold, italic, underline, etc.) — already sanitised on input
                    if (trim(strip_tags($content)) === '' || $content === '<br>') break;
                    $html .= '<p class="article-paragraph">' . $content . '</p>';
                    break;

                case 'h1':
                    if (trim(strip_tags($content)) === '') break;
                    $html .= '<h2 class="article-h1">' . $content . '</h2>';
                    break;

                case 'h2':
                    if (trim(strip_tags($content)) === '') break;
                    $html .= '<h3 class="article-h2">' . $content . '</h3>';
                    break;

                case 'h3':
                    if (trim(strip_tags($content)) === '') break;
                    $html .= '<h4 class="article-h3">' . $content . '</h4>';
                    break;

                case 'quote':
                    if (trim(strip_tags($content)) === '') break;
                    $html .= '<blockquote class="article-quote"><p>' . $content . '</p></blockquote>';
                    break;

                case 'callout':
                    if (trim(strip_tags($content)) === '') break;
                    $html .= '<div class="article-callout"><span class="article-callout-icon">💡</span><div>' . $content . '</div></div>';
                    break;

                case 'bullet':
                    if (trim(strip_tags($content)) === '') break;
                    $html .= '<ul class="article-list"><li>' . $content . '</li></ul>';
                    break;

                case 'numbered':
                    if (trim(strip_tags($content)) === '') break;
                    $numberedSeq++;
                    $html .= '<ol class="article-list article-list--numbered" start="' . $numberedSeq . '"><li>' . $content . '</li></ol>';
                    break;

                // ── MEDIA BLOCKS ─────────────────────────────────────────
                case 'image':
                    $src = $block['src'] ?? '';
                    // Skip base64 preview-only images that were never uploaded to server
                    if (! $src || str_starts_with($src, 'data:')) break;
                    $caption = e($block['caption'] ?? '');
                    $html .= '<figure class="article-figure">';
                    $html .= '<img src="' . e($src) . '" alt="' . $caption . '" class="article-image" loading="lazy">';
                    if ($caption) {
                        $html .= '<figcaption class="article-caption">' . $caption . '</figcaption>';
                    }
                    $html .= '</figure>';
                    break;

                if (str_contains($src, 'youtube.com/embed')) {
                    $html .= '
                        <div class="article-video">
                            <iframe
                                src="'.$src.'"
                                allowfullscreen
                                frameborder="0">
                            </iframe>
                        </div>
                    ';

                } else {

                    $html .= '
                        <video
                            controls
                            class="w-full rounded-xl">
                            <source src="'.$src.'">
                        </video>
                    ';
                }
                    $src = $block['src'] ?? '';
                    if (! $src) break;
                    $html .= '<div class="article-video">';
                    $html .= '<iframe src="' . e($src) . '" allowfullscreen frameborder="0" class="w-full h-full"></iframe>';
                    $html .= '</div>';
                    break;

                case 'divider':
    $html .= '<hr class="article-divider">';
    break;                    $html .= '<div class="article-divider"><span>✦</span><span>✦</span><span>✦</span></div>';
                    break;
            }
        }

        return $html ?: '<p class="text-slate-400 italic">Konten artikel belum tersedia.</p>';
    }

    /**
     * Extract plain text excerpt from block content (for previews/SEO).
     */
    public static function getExcerpt(string $content, int $length = 160): string
    {
        try {
            $blocks = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
            if (is_array($blocks)) {
                $text = '';
                $textTypes = ['paragraph', 'h1', 'h2', 'h3', 'quote', 'callout', 'bullet', 'numbered'];
                foreach ($blocks as $block) {
                    if (in_array($block['type'] ?? '', $textTypes)) {
                        $text .= strip_tags($block['content'] ?? '') . ' ';
                    }
                }
                return Str::limit(trim($text), $length);
            }
        } catch (\Throwable $e) {
            // fallback
        }
        return Str::limit(strip_tags($content), $length);
    }

    /**
     * Generate unique slug.
     */
    protected function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $base = $slug;
        $i    = 1;

        while (true) {
            $query = Article::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            if (! $query->exists()) {
                break;
            }
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}