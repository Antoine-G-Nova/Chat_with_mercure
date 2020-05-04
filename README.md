# Chat_with_Mercure
Instant chat app using Mercure Vue.js and Symfony

## Install
⚠️ (Change 'localhost' by the name of your local vhost)
1) `composer install`
2) Create "mercure" database and complete DATABASE_URL .env file with your credentials
3) `php bin/console doctrine:database:create`
4) `php bin/console make:mi`
5) `php bin/console doctrine:mi:mi`
6) `php bin/console doctrine:fix:load`
7) `yarn install`
8) `yarn watch`
9) Go to ./Mercure folder and execute the following cmd : `./mercure --jwt-key='!ChangeMe!' --addr=':3000' --debug --cors-allowed-origins='http://localhost' --publish-allowed-origins='http://localhost:3000'`  
10) Go to : http://localhost/Chat_with_mercure/public
9) Login with username : K.Dunglas || Antoine || Sarah (No password needed)
10) Enjoy!  😉