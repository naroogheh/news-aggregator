<?php

namespace App\Service\Readers;

use App\Contract\NewsReader;
use App\Dto\NewsDto;
use App\Models\Source;
use App\Traits\CurlDataGrabber;
use Guardian\GuardianAPI;
use CategoryHelper;
use AuthorHelper;
use AgencyHelper;
use Illuminate\Support\Facades\Log;

class GuardianReader extends BaseReader implements NewsReader
{
    use CurlDataGrabber;

    private $newsAgencyItem;

    public function __construct(Source $source)
    {
        parent::__construct($source);
    }

    public function getArticles($params = []): array
    {
        $this->newsAgencyItem = AgencyHelper::findBySlug('nytimes');
        $results = [];

        // Read first page of the API
        $response = $this->readApiContentPerPage($params);
        if (!$response) {
            return [];
        }

        // Add first page articles to results
        $articles = $this->grabArticlesFromResponse($response->results);
        $results = array_merge($results, $articles);

        // Check the environment and read additional pages only in production
        if (env('APP_ENV') === 'production' && $response->pages > 1) {
            $this->readAdditionalPages($params, $response->pages, $results);
        }

        return $results;
    }

    // Reads additional pages if there are more than one page in the response
    private function readAdditionalPages($params, $pageCount, &$results)
    {
        for ($i = 2; $i <= $pageCount; $i++) {
            $response = $this->readApiContentPerPage($params, $i);
            if ($response) {
                $articles = $this->grabArticlesFromResponse($response->results);
                $results = array_merge($results, $articles);
            }
        }
    }

    public function readApiContentPerPage($params, $page = 1)
    {
        $token = $this->source->api_token;
        $from = $params['from'];
        $to = $params['to'];
        $api = new GuardianAPI($token);

        $response = $api->content()
            ->setPageSize(200)
            ->setPage($page)
            ->setFromDate(new \DateTimeImmutable($from))
            ->setToDate(new \DateTimeImmutable($to))
            ->setOrderBy("relevance")
            ->fetch();

        // Check for successful response from API
        if ($response->response->status !== 'ok') {
            // Log error if response status is not 'ok'
            Log::error("Guardian API error: " . $response->response->status);
            return null;
        }
        return $response->response;
    }

    public function grabArticlesFromResponse($articles)
    {
        $results = [];
        foreach ($articles as $article) {
            // Get or create category for the article
            $category = CategoryHelper::getOrCreateCategory($article->sectionId, $article->sectionName);

            // Get or create author for the article
            $author = AuthorHelper::getOrCreateAuthor($article->author);

            // Map article data to NewsDto
            $arr = [
                'news_agency_id' => $this->newsAgencyItem->id,
                'title' => $article->webTitle,
                'unique_id_on_source' => $article->id,
                'web_url_on_source' => $article->webUrl,
                'publish_date' => $article->webPublicationDate,
                'description' => $article->webTitle,
                'image_url' => '',
                'source_id' => $this->source->id,
                'category_id' => $category->id,
                'author_id' => $author ? $author->id : null,
            ];
            // Add the mapped data to results
            $results[] = NewsDto::fromArray($arr);
        }
        return $results;
    }
}
