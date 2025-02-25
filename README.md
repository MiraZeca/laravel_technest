npm install
composer install
cp .env.example .env
php artisan key:generate
u .env fajlu stavite da vam stoji DB_CONNECTION=mysql
zatim u .env fajlu odtagujte sledece stavke: DB_HOST=127.0.0.1 DB_PORT=3306 DB_DATABASE=technest DB_USERNAME=root DB_PASSWORD=
php artisan migrate
php artisan serve
