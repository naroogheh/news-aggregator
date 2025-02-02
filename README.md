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

docker-compose up -d

# run these commands on php container (in docker container)
  
php artisan key:generate

php artisan migrate

php artisan db:seed

php artisan queue:work --queue=sources,articles

#Run this command to run the aggregator manually:

php artisan aggregator:run

```
## Usage

Open the application in your web browser at http://localhost:2080.

Api documentation (Swagger Ui ) is available at http://localhost:2080/api/documentation

because of api token limit ,in development mode, only first page of Sources will be read and stored in database.

## Contributing

 - it is better to use Elasticsearch for search and indexing.
 - use redis for caching. and queue. redis container is already in docker-compose.yml file.

