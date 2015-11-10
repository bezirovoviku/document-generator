# Document generator

Allows generation of multiple document based on DOCX template

## API Documentation
API documentation can be found on Apiary: http://docs.docgen.apiary.io/

## Requirements
PHP >= 5.4
MySQL
Composer
PHPUnit (for test only)

## Installation
1. Make sure you have both PHP and Composer instaled
  1. To install PHP and MYSQL on Debian based systems you will can to run
    
    ```
    sudo apt-get install php5-common libapache2-mod-php5 php5-cli mysql-server php5-mcrypt
    sudo php5enmod mcrypt
    ```
  2. Refer to http://getcomposer.org for installation of Composer
2. Clone this repository
3. Rename .env.example to .env and fill it with correct informations
4. Go to web folder and run (this will download basic packages)
  
  ```
  composer update 
  ```
5. Now (still in web folder)run (this will generate an key for security purposes)

  ```
  php artisan key:generate
  ```
6. Then (still in web folder)run (this will create database tables)
  
  ```
  php artisan migrate
  ```
7. Setup your web server so it points to public folder inside web folder.
  1. In apache you need to have somewhere inside your conf (to enable htaccess)
    
    ```
    AllowOverride all
    ```
  2. Also you will need mod_rewite
    
    ```
    a2enmod rewrite
    ```
