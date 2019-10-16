### 39DG Coupons

## You are required to implement a new feature for an e-commerce website: a flexible coupon system that allows for different discount
strategies without requiring major rewrites in the future.

## Requirements

* PHP >= 7.2
    * BCMath PHP Extension
    * Ctype PHP Extension
    * JSON PHP Extension
    * Mbstring PHP Extension
    * OpenSSL PHP Extension
    * PDO PHP Extension
    * Tokenizer PHP Extension
    * XML PHP Extension
* Composer
* MySQL
* npm

## Install

1. `git clone https://github.com/tpenaranda/39dg_coupons`

2. Create an empty mysql database.

3. Rename `.env.example` file to `.env` and configure DB.

4. `composer update`

5. `php artisan migrate`

6. `php artisan key:generate`

7. `npm install`

8. `npm run dev`

9. `php artisan serve` to start dev server.

## Tests (PHP SQLite driver required)

`vendor/phpunit/phpunit/phpunit`
