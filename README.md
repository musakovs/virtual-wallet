Wallet

clone repository

docker exec -it wallet-app composer install

docker-compose exec wallet-app php artisan key:generate
docker-compose exec wallet-app php artisan config-cache
docker-compose exec wallet-app php artisan migrate




