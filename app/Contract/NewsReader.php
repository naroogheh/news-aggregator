<?php

namespace App\Contract;

use App\Dto\NewsDto;

interface NewsReader
{
    function getArticles($params = []):array;

}
