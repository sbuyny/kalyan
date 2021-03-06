# REST API Kalyan

## Installation
Server with php 7.2.5 + sqlite

cd /var/www/html

mkdir kalyan

sudo chown -R www-data:www-data kalyan

cd kalyan

git init

git remote add origin https://github.com/sbuyny/kalyan

git pull origin master

composer install

composer update

cp .env.example .env

sudo nano .env(DB_CONNECTION=sqlite , DB_DATABASE=/var/www/html/kalyan/database/database.sqlite)

touch /var/www/html/kalyan/database/database.sqlite

php artisan migrate

## Run tests
Terminal /var/www/html/kalyan/ ./vendor/bin/phpunit


## kalyannayas
GET /api/kalyannayas - list kalyannayas

POST /api/kalyannayas - add kalyannaya, parameters: name(string max 255)

GET /api/kalyannayas/:id - show kalyannaya

PUT /api/kalyannayas/:id - update kalyannaya, parameters: name(string max 255)

DELETE /api/kalyannayas/:id delete kalyannaya


## kalyans
GET /api/kalyans - list kalyans 

GET /api/kalyans/kalyannaya/:id - list kalyans by kalyannaya id

POST /api/kalyans - add kalyan, parameters: name(string max 255), trubok(integer max 10), kalyannaya_id(integer)

GET /api/kalyans/:id - show kalyan

PUT /api/kalyans/:id - update kalyan, parameters: name(string max 255), trubok(integer max 10), kalyannaya_id(integer)

DELETE /api/kalyans/:id - delete kalyan

## bookings
GET /api/bookings - list bookings 

GET /api/bookings/kalyannaya/:id - list bookings by kalyannaya id

POST /api/bookings - add booking, parameters: name(string max 255), people(integer max 50), from(Y-m-d H:i:s), kalyannaya_id(integer)

GET /api/bookings/:id - show booking

GET /api/users - list users made bookings 

POST /api/search - list available kalyans, parameters: people(integer max 50), from(Y-m-d H:i:s), to(Y-m-d H:i:s)
