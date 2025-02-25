1. npm install
2. composer install
3. cp .env.example .env
4. php artisan key:generate
5. u .env fajlu stavite da vam stoji DB_CONNECTION=mysql
6. zatim u .env fajlu odtagujte sledece stavke: DB_HOST=127.0.0.1 DB_PORT=3306 DB_DATABASE=technest DB_USERNAME=root DB_PASSWORD=
7. php artisan migrate
8. composer require league/csv
9. php artisan serve
