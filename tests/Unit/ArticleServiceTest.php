<?php

namespace Tests\Unit;

use App\Services\ArticleService;
use App\Repositories\ArticleRepository;
use App\Repositories\UserRepository;
use PHPUnit\Framework\TestCase;
use Mockery;

class ArticleServiceTest extends TestCase
{
    protected $articleService;
    protected $articleRepositoryMock;
    protected $userRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();
        // Mock dependencies
        $this->articleRepositoryMock = Mockery::mock(ArticleRepository::class);
        $this->userRepositoryMock = Mockery::mock(UserRepository::class);

        // Inject mocked repositories into the service
        $this->articleService = new ArticleService(
            $this->articleRepositoryMock,
            $this->userRepositoryMock
        );
    }

    public function tearDown(): void
    {
        // Close mocks
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_articles()
    {
        // Arrange
        $data = ['limit' => 10]; // Simulating incoming data (e.g., pagination limit)
        $expectedArticles = collect(); // Replace with actual mock data if needed
        $this->articleRepositoryMock
            ->shouldReceive('getAllArticles')
            ->once()
            ->with($data)
            ->andReturn($expectedArticles);

        // Act
        $result = $this->articleService->getAllArticles($data);

        // Assert
        $this->assertEquals($expectedArticles, $result);
    }

    public function test_get_preferred_articles()
    {
        // Arrange
        $userId = 1;
        $data = ['limit' => 10]; // Simulating incoming data (e.g., pagination limit)

        // Simulating user preferences
        $preferences = collect([
            (object) ['type' => 'source', 'value' => 'BBC'],
            (object) ['type' => 'category', 'value' => 'Technology'],
            (object) ['type' => 'author', 'value' => 'John Doe']
        ]);

        // Simulating expected articles
        $expectedArticles = collect(); // Replace with actual mock data if needed

        // Mocking the userRepository's getPreferences method
        $this->userRepositoryMock
            ->shouldReceive('getPreferences')
            ->once()
            ->with($userId)
            ->andReturn($preferences);

        // Mocking the articleRepository's getArticlesByUserPreference method with argument matching using Mockery::on
        $this->articleRepositoryMock
            ->shouldReceive('getArticlesByUserPreference')
            ->once()
            ->with(
                $data,
                Mockery::on(function ($arg) {
                    return $arg instanceof \Illuminate\Support\Collection && $arg->contains('BBC');
                }),
                Mockery::on(function ($arg) {
                    return $arg instanceof \Illuminate\Support\Collection && $arg->contains('Technology');
                }),
                Mockery::on(function ($arg) {
                    return $arg instanceof \Illuminate\Support\Collection && $arg->contains('John Doe');
                })
            )
            ->andReturn($expectedArticles);

        // Act
        $result = $this->articleService->getPreferredArticles($data, $userId);

        // Assert
        $this->assertEquals($expectedArticles, $result);
    }
}
