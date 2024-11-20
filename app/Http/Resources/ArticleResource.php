<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'description' => $this->description,
            'content' => $this->content,
            'url' => $this->url,
            'image' => $this->image,
            'publication_date' => Carbon::parse($this->publication_date)->format('Y-m-d H:i:s'),
            'api_resource' => $this->api_resource,
            'news_source' => $this->news_source,
            'category' => $this->category,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
