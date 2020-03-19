# Treclon

> ### Example Laravel and vue js application.


----------
This is a clone for trello app (hens Treclon), it is a SPA built with Vue Js and Laravel. It contains simple crud operations accessed via api requests.
You can create/edit tasks and move them from a category to another.


## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)


Clone the repository

    git clone https://bitbucket.org/SoundousBahri/treclon.git

Switch to the repo folder

    cd treclon

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate


Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate --seed
    
Install Passport

    php artisan passport:install


Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

