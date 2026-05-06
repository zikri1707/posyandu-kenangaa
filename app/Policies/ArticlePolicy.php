<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    /**
     * Determine if the user can view any articles.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view
    }

    /**
     * Determine if the user can create articles.
     */
    public function create(User $user): bool
    {
        // Superadmin, Admin, and Kader can create articles
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isKader();
    }

    /**
     * Determine if the user can update the article.
     */
    public function update(User $user, Article $article): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admins can edit any, Kader can edit their own
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isKader() && $user->id === $article->user_id;
    }

    /**
     * Determine if the user can delete the article.
     */
    public function delete(User $user, Article $article): bool
    {
        // Superadmin can delete any, Kader/Admin can delete their own
        if ($user->isSuperAdmin()) {
            return true;
        }

        return ($user->isAdmin() || $user->isKader()) && $user->id === $article->user_id;
    }
}
