<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Traits\ThirdParty;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class StoreArticlesFromGuardian extends Command
{
    use ThirdParty;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:guardian {category}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store articles from The Guardian API Resource';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $category = $this->argument('category');
        $this->info('Fetching News Articles from The Guardian...');

        try {
            $url = "https://content.guardianapis.com/search?q=$category AND trending&api-key=" . env('GUARDIAN_API_KEY');
            $articles = $this->fetchNewsArticles($url);

            // If status is not returned or status is not ok
            if (!isset($articles->response->status) || $articles->response->status != "ok") {
                $this->error("Error Connecting to The Guardian. Try again Later");
                return;
            }

            if (!($articles->response->results)) {
                $this->info('No articles found.');
                return;
            }

            $this->info('Inserting Articles into database...');

            // Prepare articles data for batch insert
            $data = collect($articles->response->results)->map(function ($item) use ($category) {
                return [
                    'id' => (string) Str::uuid(),
                    'title' => $item->webTitle,
                    'description' => $item->webTitle,
                    'content' => $item->webTitle ?? null,
                    'url' => $item->webUrl,
                    'publication_date' => Carbon::parse($item->webPublicationDate)->format('Y-m-d H:i:s'),
                    'category' => $item->pillarName ?? $category,
                    'news_source' => "The Guardian",
                    'api_resource' => "The Guardian",
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            Article::insert($data);
            $this->info('Articles added successfully.');
        } catch (Exception $e) {
            $this->error('Error fetching or inserting articles: ' . $e->getMessage());
        }
    }
}
