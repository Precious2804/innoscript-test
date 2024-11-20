<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'author' => $this->faker->name,
            'content' => $this->faker->paragraph,
            'description' => $this->faker->text,
            'url' => $this->faker->url,
            'image' => $this->faker->imageUrl,
            'publication_date' => $this->faker->date,
            'api_resource' => $this->faker->word,
            'news_source' => $this->faker->company,
            'category' => $this->faker->word,
        ];
    }
}
