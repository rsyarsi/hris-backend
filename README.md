1. Package Jwt Auth | https://jwt-auth.readthedocs.io/en/develop/laravel-installation/

-   Install: composer require tymon/jwt-auth
-   Publish: php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
-   Generate secret key: php artisan jwt:secret

2. Package Laravolt Indonesia | https://github.com/laravolt/indonesia

-   Province
-   City
-   District
-   Village

-   Install : composer require laravolt/indonesia
-   Publish : php artisan vendor:publish --provider="Laravolt\Indonesia\ServiceProvider"

3.  Migration:

-   Migrate: php artisan migrate --seed && php artisan laravolt:indonesia:seed
