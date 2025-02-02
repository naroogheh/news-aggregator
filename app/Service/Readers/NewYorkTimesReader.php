<?php

namespace App\Service\Readers;

use App\Contract\NewsReader;
use App\Dto\NewsDto;
use AgencyHelper;
use App\Enum\SourceSlugEnum;
use App\Models\Source;
use App\Traits\CurlDataGrabber;
use CategoryHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use AuthorHelper;

class NewYorkTimesReader extends BaseReader implements NewsReader
{
    use CurlDataGrabber;

    private $newsAgencyItem;
    public function __construct(Source $source)
    {
        parent::__construct($source);
    }

    // Optimized function to get articles from the API
    public function getArticles($params = []): array
    {
        $this->newsAgencyItem = AgencyHelper::findBySlug(SourceSlugEnum::SLUG_NY_TIMES->value);

        if(!$this->newsAgencyItem)
        {
            echo "News Agency not found for New York Times";
            Log::error('News Agency not found for New York Times');
            return [];
        }
        $results = [];

        // Read the first page of the API
        $response = $this->readApiContentPerPage($params);
        if (!$response) {
            return $results; // early return if response is empty
        }

        // Append articles from the first page
        $results = $this->grabArticlesFromResponse($response->docs);

        // Fetch additional pages only in production environment
        if (env('APP_ENV') === 'production') {
            $pageCount = ceil($response->meta->hits / 10);
            $this->fetchAdditionalPages($params, $pageCount, $results);
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
            $from = date('Ymd',strtotime( $params['from']));
            $to = date('Ymd',strtotime( $params['to']));
            $data = [
                'api-key' => $this->source->api_token,
                'page' => $page,
                'pageSize' => 100,
                'begin_date' => $from,
                'end_date' => $to,
            ];
            $url = $this->source->base_url . '/svc/search/v2/articlesearch.json?'. http_build_query($data);

            $response = $this->sendRequest($url);
            $response = json_decode($response);
            // Log and return null if the response status is not ok
            if ($response->status !== 'OK') {
                Log::error('News API response error', ['status' => $response->status]);
                return null;
            }
            return $response->response ?? [];
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
            // Get or create category for the article
            $categoryTitle = ($article->section_name??'').' '.($article->subsection_name??'');
            $categorySlug = Str::slug($categoryTitle);
            if(strlen(trim($categorySlug)))
                $category = CategoryHelper::getOrCreateCategory($categoryTitle, $categorySlug);
            else
                $category = null;

            // Get or create author for the article
            $authorName = str_replace('By ','', $article->byline->original);
            $author = AuthorHelper::getOrCreateAuthor(trim($authorName));
            $arr = [
                'news_agency_id' => $this->newsAgencyItem->id,
                'title' => $article->abstract,
                'unique_id_on_source' => $article->_id,
                'web_url_on_source' => $article->web_url,
                'publish_date' => $article->pub_date,
                'description' => $article->lead_paragraph,
                'image_url' => '',
                'source_id' => $this->source->id,
                'category_id' =>$category? $category->id:null,
                'author_id' => $author?->id,
            ];
            $results[] = NewsDto::fromArray($arr); // Add the mapped article to results
        }
        return $results;
    }
}
