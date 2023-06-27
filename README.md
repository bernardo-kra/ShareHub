composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan migrate:refresh
php artisan db:seed
php artisan serve