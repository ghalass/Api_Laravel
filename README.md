## About Laravel

Welcome to API, end-points [GET, POST, PUT, DELETE]:

-   Sites
-   Typeparcs
-   Parcs
-   Engins
-   Typepannes
-   Pannes
-   Typelubrifiants
-   Lubrifiants

## Used commandes

-   laravel new Api_Laravel
-   php artisan install:api
-   install sanctum
-   php artisan make:model Post -a --api
    -- for creating : model, factory, migration, seeder, request, controller policy
-   php artisan migrate
-   php artisan make:controller AuthController
-   php artisan lang:publish
    -- On va ajouter la traduction française. Déjà dans le fichier .env : APP_LOCALE=fr
    -- composer require laravel-lang/common --dev
    -- php artisan lang:update
-   php artisan config:clear THEN php artisan migrate --force
