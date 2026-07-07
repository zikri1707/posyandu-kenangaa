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
        // Get 3 upcoming or ongoing schedules (not ended yet and not completed/cancelled)
        $schedules = Schedule::with('posyandu')
            ->where('end_time', '>=', now())
            ->whereNotIn('status', ['completed', 'cancelled', 'Completed', 'Cancelled'])
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
    public function about(\App\Services\AboutPageService $aboutPageService)
    {
        $misis = $aboutPageService->getMissions();
        $tujuans = $aboutPageService->getGoals();
        $kaders = $aboutPageService->getCadres();
        $sasaranCount = $aboutPageService->getSasaranCount();
        $posyanduCount = $aboutPageService->getPosyanduCount();

        return view('public.about.about', compact('misis', 'tujuans', 'kaders', 'sasaranCount', 'posyanduCount'));
    }

    /**
     * Display the contact page
     */
    public function contact()
    {
        return view('public.contact');
    }
}
