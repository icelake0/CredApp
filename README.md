# Cred App

This App provide REST API for exchange rates for a user set base currency and notification for rate threshold based on http://data.fixer.io.

Also include an endpoint for loan monthly repayment calculation.

You can see APIs details in postman collection.

## Local Setup Guide

1) Clone the project
    ```bash
    git clone https://github.com/icelake0/CredApp.git
    ```
2) Change directory to the project root
    ```bash
    cd CredApp
    ```
3) Copy .env.example to .env

    ```bash
    cp .env.example .env
    ```
4) Setup database connection by changing these in the .env
    - DB_CONNECTION=mysql
    - DB_HOST=localhost
    - DB_PORT=3308
    - DB_DATABASE={{Your DB Name}}
    - DB_USERNAME={{Your DB User Name}}
    - DB_PASSWORD={{Your DB Password}}

5) Setup mailtrap by changing these in the .env
    - MAIL_DRIVER=smtp
    - MAIL_HOST=smtp.mailtrap.io
    - MAIL_PORT=2525
    - MAIL_USERNAME={{Your MailTrap User Name}}
    - MAIL_PASSWORD={{Your MailTrap Password}}
    - MAIL_FROM_ADDRESS=from@example.com
    - MAIL_FROM_NAME=Example

6) Setup Fixer by changing these in the .env
    - FIXER_HOST = http://data.fixer.io
    - FIXER_API_KEY = {{Your Fixer Api Key}}

7) Install dependencies via composer

    ```bash
    composer install
    ```

8) run db migration
    ```bash
    php artisan migrate
    ```
9) Setup JWT Auth
     ```bash
   php artisan cache:clear
    ```
     ```bash
    php artisan config:clear
    ```
     ```bash
    php artisan jwt:secret
    ```

10) serve app
    ```bash
    php artisan serve
    ```

11) You can connect to the app locally on this host if you did not change the default port

    ```bash
    localhost:8000
    ```
11) The App is set to send threshold notification hourly, but you can manually trigger the notification by running the command below in the root directory of the app.

    ```bash
    php artisan threshold:alert-users
    ```

## Testing Apis on Heroku

- Host : https://gbemileke-cred-app.herokuapp.com
- Post man collection: https://www.getpostman.com/collections/c17c10d64e264a406fd6

    [![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/c17c10d64e264a406fd6)

## Testing Apis on Local Host

> To test on local your local, symply change the host variable in the postman collection to your localhost url eg localhost:8080

## License
This was built with Laravel framework

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).