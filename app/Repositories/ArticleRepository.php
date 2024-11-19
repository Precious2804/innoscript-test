<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository
{
    public function getAllArticles($data)
    {
        $articles = Article::filter($data)->orderBy('created_at', 'DESC')->paginate($data['limit'] ?? 10);
        return $articles;
    }
}
