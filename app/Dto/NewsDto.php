<?php

namespace App\Dto;

class NewsDto
{
    public function __construct(
        public string $title,
        public string $unique_id_on_source,
        public string $web_url_on_source,
        public string $publish_date,
        public string $description,
        public string $image_url,
        public int $news_agency_id,
        public int $source_id,
        public int $category_id,
        public int $author_id
    ) {}

    public static function fromArray(array $arr): self
    {
        return new self(
            title: $arr['title'],
            unique_id_on_source: $arr['unique_id_on_source'],
            web_url_on_source: $arr['web_url_on_source'],
            publish_date: $arr['publish_date'],
            description: $arr['description'],
            image_url: $arr['image_url'],
            news_agency_id: $arr['news_agency_id'],
            source_id: $arr['source_id'],
            category_id: $arr['category_id'],
            author_id: $arr['author_id']
        );
    }

    public function toArray(): array
    {
        return (array) $this;
    }
}
