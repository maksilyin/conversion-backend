#Развернуть приложение
./init.sh

docker-compose exec app bash

php artisan reverb:start
php artisan queue:work
php artisan queue:work --queue=service_messages

php artisan make:controller ControllerName