<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository
{
    public function getAllArticles($data)
    {
        $articles = Article::orderBy('created_at', 'DESC')->paginate($data['limit'] ?? 10);
        return $articles;
    }
}
