<?php

namespace App\Enum;

enum SourceSlugEnum: string
{
    case SLUG_GUARDIAN = 'Guardian';
    case SLUG_NEWS_ORG = 'newsorg';
    case SLUG_NY_TIMES = 'nytimes';

}
