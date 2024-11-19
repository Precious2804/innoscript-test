<?php

namespace App\ModelFilters;

use Carbon\Carbon;
use EloquentFilter\ModelFilter;

class ArticleFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function keyword($value)
    {
        return $this->where(function ($query) use ($value) {
            $query->where('title', 'LIKE', "%{$value}%")
                ->orWhere('author', 'LIKE', "%{$value}%")
                ->orWhere('description', 'LIKE', "%{$value}%")
                ->orWhere('content', 'LIKE', "%{$value}%")
                ->orWhere('news_source', 'LIKE', "%{$value}%")
                ->orWhere('category', 'LIKE', "%{$value}%");
        });
    }

    public function category($value)
    {
        return $this->where(function ($query) use ($value) {
            $query->where('category', 'LIKE', "%{$value}%");
        });
    }

    public function source($value)
    {
        return $this->where(function ($query) use ($value) {
            $query->where('api_resource', 'LIKE', "%{$value}%")
                ->orWhere('news_source', 'LIKE', "%{$value}%");
        });
    }

    public function publicationDate($date)
    {
        return $this->whereDate('publication_date', '=', Carbon::parse($date)->format('Y-m-d'));
    }
}
