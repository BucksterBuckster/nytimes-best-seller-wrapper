<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class NYTBestSellersService
{
    private string $baseUrl = 'https://api.nytimes.com/svc/books/v3/lists/best-sellers/history.json';

    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.nyt.key');
    }

    public function getBestSellers(array $params): array
    {
        $cacheKey = 'nyt_bestsellers_'.md5(json_encode($params));

        return Cache::remember($cacheKey, now()->addHours(1), function () use ($params) {
            $response = Http::get("{$this->baseUrl}/lists/best-sellers/history.json", [
                'api-key' => $this->apiKey,
                ...$this->filterParams($params),
            ]);

            if ($response->failed()) {
                throw new \Exception('NYT API request failed: '.$response->body());
            }

            return $response->json();
        });
    }

    private function filterParams(array $params): array
    {
        return array_filter([
            'author' => $params['author'] ?? null,
            'isbn' => $params['isbn'] ?? null,
            'title' => $params['title'] ?? null,
            'offset' => $params['offset'] ?? null,
        ]);
    }
}
