<?php

namespace App\Service\Readers;

use App\Models\Source;

class BaseReader
{
    protected $source;
    function __construct(Source $source)
    {
        $this->source = $source;
    }
}
