<?php

namespace App\Service;

use App\Contract\NewsReader;
use App\Models\Source;

class NewsOrgReader implements NewsReader
{
    use \App\Traits\CurlDataGrabber;

    private  $source;
    function __construct(Source $source)
    {
        $this->source = $source;
    }
    function getArticles($params = [])
    {
        $url = $this->source->base_url;

    }
}
