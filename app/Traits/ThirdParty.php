<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait ThirdParty
{
    function fetchNewsArticles($url)
    {
        $response = Http::acceptJson()->get($url);
        return json_decode($response);
    }
}
