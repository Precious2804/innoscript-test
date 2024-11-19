<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Traits\ThirdParty;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class StoreArticlesFromNewYorkTimes extends Command
{
    use ThirdParty;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:nytimes {category}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store articles from New York Times API Resource';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $category = $this->argument('category');
        $this->info('Fetching News Articles from New York Times...');

        try {

            // Fetch News articles from newsapi.org
            $today = Carbon::today()->format('Ymd');
            $url = "https://api.nytimes.com/svc/search/v2/articlesearch.json?q=$category&begin_date=$today&end_date=$today&api-key=" . env('NEW_YORK_TIMES_API_KEY');
            $articles = $this->fetchNewsArticles($url);

            // If status is not returned or status not ok
            if (!isset($articles->status) || $articles->status != "OK") {
                $this->error("Error Connecting to New York Times. Try again Later");
                return;
            }

            if (!($articles->response->docs)) {
                $this->info('No articles found.');
                return;
            }

            $this->info('Inserting Articles into database...');

            // Prepare articles data for batch insert
            $data = collect($articles->response->docs)->map(function ($item) use ($category) {
                return [
                    'id' => (string) Str::uuid(),
                    'title' => $item->abstract ?? $item->snippet,
                    'author' => $item->byline->original,
                    'description' => $item->snippet ?? $item->abstract,
                    'content' => $item->lead_paragraph ?? $item->snippet ?? $item->abstract,
                    'image' => $item->multimedia[0]->url ?? null,
                    'url' => $item->web_url,
                    'publication_date' => $item->pub_date,
                    'category' => $category,
                    'news_source' => $item->source,
                    'api_resource' => "New York Times",
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
