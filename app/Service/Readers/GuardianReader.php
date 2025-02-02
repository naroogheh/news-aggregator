<?php

namespace App\Service\Readers;

use App\Contract\NewsReader;
use App\Dto\NewsDto;
use App\Models\Source;
use App\Traits\CurlDataGrabber;
use Guardian\GuardianAPI;
use CategoryHelper;
use AuthorHelper;

class GuardianReader extends BaseReader implements NewsReader
{
    use CurlDataGrabber;

    private $newsAgencyService;
    private $newsAgencyItem;

    function __construct(Source $source, $newsAgencyService)
    {
        $this->newsAgencyService = $newsAgencyService;
        $this->newsAgencyItem = $newsAgencyService->findBySlug('guardian');
        parent::__construct($source);
    }
    function getArticles($params = []): array
    {
        $results  = [];
        //read first page of api
        $response = $this->readApiContentPerPage($params);
        if(!$response)
            return [];
        // add page 1  news to results array
        $articles = $this->grabArticlesFromResponse($response->results);
        $results = array_merge($results, $articles);

        // check environment and run loop to read other pages on production only
        // in development mode it will read only first page
        if (env('APP_ENV') == 'production')
        {
            $pageCount = $response->pages;
            if($pageCount > 1){
                //read other pages
                for ($i = 2; $i <= $pageCount; $i++) {
                    $response = $this->readApiContentPerPage($params,$i);
                    $response = $response->response;
                    // add other page news to results array
                    $articles = $this->grabArticlesFromResponse($response->results);
                    $results = array_merge($results, $articles);
                }
            }
        }


        return $results;
    }
    function readApiContentPerPage($params,$page = 1)
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
        if($response->response->status !== 'ok') {
            //add log
            return null;
        }
        return $response->response;
    }
    function grabArticlesFromResponse($articles)
    {

        $results = [];
        foreach ($articles as $article) {
            $category = CategoryHelper::getOrCreateCategory($article->sectionId,$article->sectionName);
            $author = AuthorHelper::getOrCreateAuthor($article->author);
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
                'author_id' => $author?$author->id:null,
            ];
            $results[] = NewsDto::fromArray($arr);
        }
        return $results;

    }
}
