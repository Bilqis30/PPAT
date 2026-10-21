<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap for search engine web crawlers.
     */
    public function index(): Response
    {
        $materis = Materi::query()
            ->whereNotNull('slug')
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at', 'published_at']);

        $content = view('sitemap', compact('materis'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
