### Setup Guide

Copy `.env.example` to `.env`

Run `composer install` and `npm install`

After completion make sure Docker is running and run `./vendor/bin/sail up -d`

Next we need to create the tables so run `./vendor/bin/sail php artisan migrate`

Now direct to http://localhost
