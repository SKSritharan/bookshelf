## Bookshelf

This is a personal book library app build with:
* [Laravel](https://laravel.com)
* [Filament](https://filamentphp.com)
* [Blueprint](https://blueprint.laravelshift.com)
* [Pest](https://pestphp.com/)

### Structure

The app includes the following entities:

* Book
* Author
* Category

## Installation

* Checkout the code: `git clone git@github.com:SKSritharan/bookshelf.git`
* Install dependencies: `composer install` and `npm install`
* Configure .env: `cp cp .env.example .env` and change to `APP_URL=http://localhost:8000`
* `php arisan key:generate`
* `php artisan migrate`
* `php artisan serve`
* `php artisan storage:link`

## Usage
  
* Create admin user by running: `php artisan make:filament-user`
* Open http://localhost:8000/admin/login and login with the newly created user
* The database is empty by default (TODO: add a seeder with authors, categories, books to give user a flavour of the app)
