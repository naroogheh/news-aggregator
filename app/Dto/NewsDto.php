<?php

namespace App\Dto;

class NewsDto
{
    public static function fromArry(array $arr)
    {
        $dto = new NewsDto();
        $dto->title = $arr['title'];
        $dto->description = $arr['description'];
        $dto->url = $arr['url'];
        $dto->urlToImage = $arr['urlToImage'];
        $dto->publishedAt = $arr['publishedAt'];
        return $dto;
    }

    function toArray()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'urlToImage' => $this->urlToImage,
            'publishedAt' => $this->publishedAt,
        ];
    }

}
