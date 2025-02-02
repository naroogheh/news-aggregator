<?php

namespace App\Service\Readers;

use App\Contract\NewsReader;
use App\Dto\NewsDto;
use App\Models\Source;
use App\Traits\CurlDataGrabber;
use CategoryHelper;
use AgencyHelper;
use Illuminate\Support\Facades\Log;
use jcobhams\NewsApi\NewsApi;

class NewsOrgReader extends BaseReader implements NewsReader
{
    use CurlDataGrabber;

    private $newsAgencyService;


    function __construct(Source $source, $newsAgencyService)
    {
        $this->newsAgencyService = $newsAgencyService;
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
        try {
            $token = $this->source->api_token;
            $agencies = $this->readNewsAgencyItems($token);
            $this->saveAgencies($agencies);
            var_dump($agencies);
            exit;
            $from = $params['from'];
            $to = $params['to'];
            $api = new NewsApi($token);
            $response = $api->getEverything(
                '*',
                '',
                '',
                '',
                $from,
                $to,
                'en',
                'publishedAt',  100,
                $page);
            if($response->status !== 'ok') {
                //add log
                return null;
            }
            return $response->articles;
        }
        catch (\Exception $e) {
            //add log
            Log::log('error', $e->getMessage());
            return null;
        }

    }
    function readNewsAgencyItems($token)
    {
        try {
            $api = new NewsApi($token);
            $sources = $api->getSources(null,'en',null);
            if($sources->status !== 'ok') {
                return null;
            }
            return $sources->sources;
        }
        catch (\Exception $e) {
            //add log
            var_dump($e->getMessage().' , '.$e->getLine().' , '.$e->getFile());
            Log::log('error', $e->getMessage());
            return null;
        }

    }
    function saveAgencies($agencies)
    {
        foreach ($agencies as $agency) {
            $agency = AgencyHelper::getOrCreateCategory($agency->id,$agency->name,$agency->category);
        }
    }
    function grabArticlesFromResponse($articles)
    {

        $results = [];
        foreach ($articles as $article) {
            $agency = AgencyHelper::getOrCreateCategory($article->name,$article->name);
            $category = CategoryHelper::readFromAgency($article->sectionId,$article->sectionName);
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
                'author_id' => null,
            ];
            $results[] = NewsDto::fromArray($arr);
        }
        return $results;

    }
}
