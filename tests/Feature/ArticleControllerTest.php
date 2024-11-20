<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_all_articles()
    {
        // Arrange
        $user = User::factory()->create();
        Article::factory()->count(5)->create();

        // Act
        $response = $this->actingAs($user)->getJson(route('article.index'));

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'author',
                            'content',
                            'description',
                            'url',
                            'image',
                            'publication_date',
                            'api_resource',
                            'news_source',
                            'category',
                            'created_at',
                        ],
                    ],
                    'links' => [
                        'first',
                        'last',
                        'prev',
                        'next',
                    ],
                    'meta' => [
                        'current_page',
                        'from',
                        'last_page',
                        'links',
                        'path',
                        'per_page',
                        'to',
                        'total',
                    ],
                ],
                'message',
                'status',
            ]);
    }


    public function test_can_fetch_a_single_article()
    {
        // Arrange
        $user = User::factory()->create();
        $article = Article::factory()->create();

        // Act
        $response = $this->actingAs($user)->getJson(route('article.show', $article->id));

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'author',
                    'content',
                    'description',
                    'url',
                    'image',
                    'publication_date',
                    'api_resource',
                    'news_source',
                    'category',
                    'created_at',
                ],
                'message',
                'status',
            ]);
    }

    public function test_can_fetch_articles_based_on_preferences()
    {
        // Arrange
        $user = User::factory()->create();
        Article::factory()->count(3)->create();

        // Mock user preferences and inject dependencies if necessary.

        // Act
        $response = $this->actingAs($user)->getJson(route('article.preferences'));

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'author',
                            'content',
                            'description',
                            'url',
                            'image',
                            'publication_date',
                            'api_resource',
                            'news_source',
                            'category',
                            'created_at',
                        ],
                    ],
                    'links' => [
                        'first',
                        'last',
                        'prev',
                        'next',
                    ],
                    'meta' => [
                        'current_page',
                        'from',
                        'last_page',
                        'links',
                        'path',
                        'per_page',
                        'to',
                        'total',
                    ],
                ],
                'message',
                'status',
            ]);
    }
}
