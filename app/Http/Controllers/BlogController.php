<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    public function index()
    {
        // Log de tous les articles sans filtres
        $allArticles = Article::all();
        Log::info('Nombre total d\'articles : ' . $allArticles->count());

        foreach ($allArticles as $article) {
            Log::info('Article non filtré :', [
                'id' => $article->id,
                'title' => $article->title,
                'is_published' => $article->is_published,
                'published_at' => $article->published_at
            ]);
        }

        // Log des articles avec les filtres
        $articles = Article::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        Log::info('Nombre d\'articles filtrés : ' . $articles->count());

        foreach ($articles as $article) {
            Log::info('Article filtré :', [
                'id' => $article->id,
                'title' => $article->title,
                'is_published' => $article->is_published,
                'published_at' => $article->published_at
            ]);
        }

        return view('blog.index', compact('articles'));
    }

    public function show(Article $article)
    {
        if (!$article->is_published || $article->published_at > now()) {
            abort(404);
        }

        return view('blog.show', compact('article'));
    }
}
