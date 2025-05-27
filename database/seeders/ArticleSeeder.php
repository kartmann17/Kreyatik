<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Comment bien démarrer avec Laravel',
                'content' => 'Laravel est un framework PHP moderne et élégant...',
                'is_published' => true,
                'published_at' => now(),
                'user_id' => 1
            ],
            [
                'title' => 'Les meilleures pratiques de développement web',
                'content' => 'Le développement web moderne nécessite de suivre certaines bonnes pratiques...',
                'is_published' => true,
                'published_at' => now(),
                'user_id' => 1
            ],
            [
                'title' => 'Optimiser les performances de votre site',
                'content' => 'Les performances sont cruciales pour le succès d\'un site web...',
                'is_published' => true,
                'published_at' => now(),
                'user_id' => 1
            ]
        ];

        foreach ($articles as $article) {
            Article::create([
                'title' => $article['title'],
                'slug' => Str::slug($article['title']),
                'content' => $article['content'],
                'is_published' => $article['is_published'],
                'published_at' => $article['published_at'],
                'user_id' => $article['user_id']
            ]);
        }
    }
}
