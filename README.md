# News Aggregator

A Laravel-based news aggregation system that collects news from multiple sources using queued jobs.

## Features

- Automated news collection from multiple sources
- Queue-based processing using Laravel jobs
- Source caching for improved performance
- Docker support for easy deployment

## Installation

1. Clone the repository:
```bash
git clone https://github.com/naroogheh/news-aggregator.git

cd news-aggregator

cp .env.example .env

cp .env.example .env

docker-compose up -d

docker-compose exec app composer install

docker-compose exec app php artisan key:generate

docker-compose exec app php artisan migrate

