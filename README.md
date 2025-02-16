# NewsAggregator

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE.md)

## How to use:
**For initializing the project:**
- Clone the project
- Run `cd news-aggregator`
- Run `cp .env.example .env`
- Run `docker network create default-network`
- Run `docker compose up --build -d`

- Run `docker exec -it php chmod 777 -R storage`
- Run `docker exec -it php composer install`
- Run `docker exec -it php php artisan migrate`
- Run `docker compose restart`

The app fetches data from the three configured news sources every minute for testing purposes. In production, this interval can be adjusted to every 10 minutes in `routes/console.php`.

The API is accessible via `localhost:8081/api/articles`. This can be filtered with the following query parameters:
- `source`: The source of the news
- `category`: The category of the news
- `author`: The author of the news
- `date`: The date of the news
- `news_service`: The news service of the news

**For running feature and unit tests:**
- Run `docker exec -it php php artisan migrate --env=testing`
- Run `docker exec -it php php artisan test`
