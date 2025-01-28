<?php

namespace App\Service\Readers;

use App\Contract\NewsReader;
use App\Models\Source;
use App\Traits\CurlDataGrabber;

class NewsOrgReader implements NewsReader
{
    use CurlDataGrabber;

    private  $source;
    function __construct(Source $source)
    {
        $this->source = $source;
    }
    function getArticles($params = []): array
    {
        $url = $this->source->base_url;

        return [];
    }
}
