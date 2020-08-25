Wallet

docker-compose exec wallet-db bash

mysql -u root -p

GRANT ALL ON wallet.* TO 'walletuser'@'%' IDENTIFIED BY 'qwerty';

FLUSH PRIVILEGES;

exit;

exit;

docker-compose exec wallet-app php artisan key:generate
docker-compose exec wallet-app php artisan config-cache
docker-compose exec wallet-app php artisan migrate

npm install && npm run dev




