<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController extends Controller
{
    public function __construct(protected ArticleService $service)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['source', 'category', 'author', 'date', 'news_service']);
        $articles = $this->service->searchArticles($filters);

        return ArticleResource::collection($articles);
    }
}
