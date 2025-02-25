1. npm install
2. composer install
3. composer require league/csv
4. cp .env.example .env
5. php artisan key:generate
6. u .env fajlu stavite da vam stoji DB_CONNECTION=mysql
7. zatim u .env fajlu odtagujte sledece stavke: DB_HOST=127.0.0.1 DB_PORT=3306 DB_DATABASE=technest DB_USERNAME=root DB_PASSWORD=
8. php artisan migrate
9. . php artisan serve
