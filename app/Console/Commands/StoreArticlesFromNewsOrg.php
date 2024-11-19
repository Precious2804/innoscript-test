<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Traits\ThirdParty;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;


class StoreArticlesFromNewsOrg extends Command
{
    use ThirdParty;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:newsorg {category}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store articles from NewsAPI.org';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $category = $this->argument('category');
        $this->info('Fetching News Articles from NewsAPI.org...');

        try {

            // Fetch News articles from newsapi.org
            $yesterday = Carbon::yesterday()->format('Y-m-d');
            $today = Carbon::today()->format('Y-m-d');
            $url = "https://newsapi.org/v2/everything?q=$category&from=$yesterday&to=$today&pageSize=10&sortBy=popularity&apiKey=" . env('NEWSORG_API_KEY');
            $articles = $this->fetchNewsArticles($url);

            // If status is not returned or status not ok
            if (!isset($articles->status) || $articles->status != "ok") {
                $this->error("Error Connecting to NewsAPI.org. Try again Later");
                return;
            }

            if (!($articles->articles)) {
                $this->info('No articles found.');
                return;
            }

            $this->info('Inserting Articles into database...');

            // Prepare articles data for batch insert
            $data = collect($articles->articles)->filter(function ($item) {
                return $item->title !== '[Removed]'; //filter out data that has been Removed
            })->map(function ($item) use ($category) {
                return [
                    'id' => (string) Str::uuid(),
                    'title' => $item->title,
                    'author' => $item->author,
                    'description' => $item->description,
                    'content' => $item->content ?? $item->title,
                    'image' => $item->urlToImage,
                    'url' => $item->url,
                    'publication_date' => Carbon::parse($item->publishedAt)->format('Y-m-d H:i:s'),
                    'category' => $category,
                    'news_source' => $item->source->name,
                    'api_resource' => "NewsAPI.org",
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
