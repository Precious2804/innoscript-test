<?php

namespace App\Services;

use App\Repositories\ArticleRepository;
use App\Repositories\UserRepository;

class ArticleService
{
    protected $articleRepository;
    protected $userRepository;

    public function __construct(ArticleRepository $articleRepository, UserRepository $userRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->userRepository = $userRepository;
    }

    public function getAllArticles($data)
    {
        return $this->articleRepository->getAllArticles($data);
    }

    public function getPreferredArticles($data, $userId)
    {
        // Get User Preferences grouped by type
        $preferences = $this->userRepository->getPreferences($userId);
        $sources = $preferences->where('type', 'source')->pluck('value');
        $categories = $preferences->where('type', 'category')->pluck('value');
        $authors = $preferences->where('type', 'author')->pluck('value');

        return $this->articleRepository->getArticlesByUserPreference($data, $sources, $categories, $authors);
    }
}
