<?php

namespace App\Service\Readers;

use App\Contract\NewsReader;
use App\Dto\NewsDto;
use App\Models\Source;
use App\Traits\CurlDataGrabber;
use Guardian\GuardianAPI;

class GuardianReader extends BaseReader implements NewsReader
{
    use CurlDataGrabber;

    function __construct(Source $source)
    {
       parent::__construct($source);
    }
    function getArticles($params = []): array
    {
        $results  = [];
        $response = $this->readPageContent($params);
        if(!$response)
            return [];
        $articles = $response->results;
        $pageCount = $response->pages;
        dd($pageCount);
        if($pageCount > 1){
            for ($i = 2; $i <= $pageCount; $i++) {
                $response = $this->readPageContent($params,$i);
                $response = $response->response;
                $articles = $response->results;
            }
        }
        dd($pageCount);
        return [];
    }
    function readPageContent($params,$page = 1)
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
            $arr = [];
            $results[] = NewsDto::fromArry($arr);
        }

    }
}
