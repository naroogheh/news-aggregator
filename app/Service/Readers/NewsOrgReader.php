<?php

namespace App\Service\Readers;

use App\Contract\NewsReader;
use App\Dto\NewsDto;
use App\Models\Source;
use CategoryHelper;
use AgencyHelper;
use Illuminate\Support\Facades\Log;
use jcobhams\NewsApi\NewsApi;

class NewsOrgReader extends BaseReader implements NewsReader
{

    public function __construct(Source $source)
    {
        parent::__construct($source);

        // Initialize news agencies from the API
        $this->initializeNewsAgencies($this->source->api_token);
    }

    // Optimized function to get articles from the API
    public function getArticles($params = []): array
    {
        $results = [];

        // Read the first page of the API
        $response = $this->readApiContentPerPage($params);
        if (!$response) {
            return $results; // early return if response is empty
        }

        // Append articles from the first page
        $results = $this->grabArticlesFromResponse($response);

        // Fetch additional pages only in production environment
        if (env('APP_ENV') === 'production') {
            $this->fetchAdditionalPages($params, $response->pages, $results);
        }

        return $results;
    }

    // Optimized fetching additional pages in production
    private function fetchAdditionalPages($params, $pageCount, &$results)
    {
        for ($i = 2; $i <= $pageCount; $i++) {
            $response = $this->readApiContentPerPage($params, $i);
            if ($response && isset($response->results)) {
                $results = array_merge($results, $this->grabArticlesFromResponse($response->results));
            }
        }
    }

    // Function to read API content with pagination
    public function readApiContentPerPage($params, $page = 1)
    {
        try {
            $api = new NewsApi($this->source->api_token);
            $response = $api->getEverything(
                '*', null, null, null, $params['from'], $params['to'], 'en', null, 100, $page
            );

            // Log and return null if the response status is not ok
            if ($response->status !== 'ok') {
                Log::error('News API response error', ['status' => $response->status]);
                return null;
            }

            return $response->articles ?? [];
        } catch (\Exception $e) {
            // Catch and log any exceptions
            Log::error('Error fetching news data from API', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return null;
        }
    }

    // Function to initialize news agencies from the API
    private function initializeNewsAgencies($token)
    {
        try {
            $agencies = $this->readNewsAgencyItems($token);
            $this->saveAgencies($agencies);
        } catch (\Exception $e) {
            Log::error('Error initializing news agencies', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }

    // Function to fetch and return news agency items
    private function readNewsAgencyItems($token)
    {
        try {
            $api = new NewsApi($token);
            $sources = $api->getSources(null, 'en', null);
            return $sources->status === 'ok' ? $sources->sources : [];
        } catch (\Exception $e) {
            Log::error('Error fetching news agencies', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return [];
        }
    }

    // Function to save agencies
    private function saveAgencies(array $agencies)
    {
        foreach ($agencies as $agency) {
            AgencyHelper::getOrCreateAgency($agency->id, $agency->name, $agency->category);
        }
    }

    // Function to map articles from the API response to NewsDto
    private function grabArticlesFromResponse($articles)
    {
        $results = [];
        foreach ($articles as $article) {
            $agency = AgencyHelper::getOrCreateAgency($article->source->name, $article->source->name, '');
            if (!$agency || !strlen(trim($agency->category))) {
                continue; // skip if agency is invalid or category is empty
            }

            $category = CategoryHelper::getOrCreateCategory($agency->category, $agency->category);
            $arr = [
                'news_agency_id' => $agency->id,
                'title' => $article->title,
                'unique_id_on_source' => $article->url,
                'web_url_on_source' => $article->url,
                'publish_date' => $article->publishedAt,
                'description' => $article->description,
                'image_url' => $article->urlToImage,
                'source_id' => $this->source->id,
                'category_id' => $category->id,
                'author_id' => null,
            ];
            $results[] = NewsDto::fromArray($arr); // Add the mapped article to results
        }
        return $results;
    }
}
