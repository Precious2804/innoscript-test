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

    public function getArticlesByUserPreference($data, $sources, $categories, $authors)
    {
        $articles = Article::query()
            ->filter($data)
            ->where(function ($query) use ($sources, $categories, $authors) {
                // Match articles based on any of the preferred news sources
                if ($sources->isNotEmpty()) {
                    $query->orWhereIn('news_source', $sources);
                }

                // Match articles based on any of the preferred categories
                if ($categories->isNotEmpty()) {
                    $query->orWhereIn('category', $categories);
                }

                // Match articles based on any of the preferred authors
                if ($authors->isNotEmpty()) {
                    $query->orWhereIn('author', $authors);
                }
            })
            ->paginate($data['limit'] ?? 10);

        return $articles;
    }
}
