<?php

return [
    [
        'title' => 'guardian',
        'slug' => 'guardian',
        'base_url' => 'https://content.guardianapis.com',
        'reader_class' => 'GuardianReader',
        'api_token' => 'c9a2e89a-4dff-460e-97b6-616a87062611',
        'status' => \App\Enum\Status::Active->value,
    ],
    [
        'title' => 'newsorg',
        'slug' => 'newsorg',
        'base_url' => 'https://newsapi.org/v2',
        'reader_class' => 'NewsOrgReader',
        'api_token' => '27a8ea5ad22f4660a2347a0ea2918198',
        'status' => \App\Enum\Status::Active->value,
    ],
    [
        'title' => 'nytimes',
        'slug' => 'nytimes',
        'base_url' => 'https://api.nytimes.com',
        'reader_class' => 'NewYorkTimesReader',
        'api_token' => 'CvyAsMteD7kvPsT2iAkZc8gGzGHPqHrX',
        'status' => \App\Enum\Status::Active->value,
    ],
];
