1. Package Jwt Auth | https://jwt-auth.readthedocs.io/en/develop/laravel-installation/

-   install : composer require tymon/jwt-auth
-   publish : php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
-   generate secret key : php artisan jwt:secret

2. Package Laravolt Indonesia | https://github.com/laravolt/indonesia

-   Province
-   City
-   District
-   Village

-   install : composer require laravolt/indonesia
-   publish : php artisan vendor:publish --provider="Laravolt\Indonesia\ServiceProvider"
    -migrate : php artisan migrate
    db seed : php artisan laravolt:indonesia:seed
    =============================
