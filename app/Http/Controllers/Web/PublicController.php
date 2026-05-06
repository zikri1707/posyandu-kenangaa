<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Schedule;

class PublicController extends Controller
{
    /**
     * Display the public home page
     * Shows: posyandu profile, 3 upcoming schedules, 3 latest articles
     */
    public function home()
    {
        // Get 3 upcoming schedules (future dates, ordered by start_time)
        $schedules = Schedule::with('posyandu')
            ->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->limit(3)
            ->get();

        // Get 3 latest published articles
        $articles = Article::with(['category', 'user'])
            ->where('status', 'published')
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('public.home', compact('schedules', 'articles'));
    }

    /**
     * Display the about page
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Display the contact page
     */
    public function contact()
    {
        return view('public.contact');
    }
}
