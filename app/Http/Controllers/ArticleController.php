<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    use ApiResponse;

    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $articles = $this->articleService->getAllArticles($data);
        return ApiResponse::successResponseWithData(ArticleResource::collection($articles)->response()->getData(), "Articles Retrieved", Response::HTTP_OK);
    }

    public function show(Article $article)
    {
        return ApiResponse::successResponseWithData(new ArticleResource($article), "Single Article Retrieved", Response::HTTP_OK);
    }

    public function preferences(Request $request)
    {
        $data = $request->all();
        $getPreferredArticles = $this->articleService->getPreferredArticles($data, Auth::user()->id);
        return ApiResponse::successResponseWithData(ArticleResource::collection($getPreferredArticles)->response()->getData(), "Articles for User Preferences", Response::HTTP_OK);
    }
}
