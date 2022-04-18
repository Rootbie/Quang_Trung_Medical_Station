# backend
- XAMPP version 7.x (7.4.27 or 7.4.28...)

## Download composer
- Open https://getcomposer.org
- Click DOWNLOAD
- Under “Windows Installer”, click the “Composer-Setup.exe” link to download the installer.
- Run the installer. Follow the prompts.
- When the installation is complete, open CMD (terminal) and type: composer
- If you receive a list of commands, Composer is properly installed on your machine.

## Open extension sodium in php.ini
- Install XAMPP
- Open and click Config of Apache
- Click PHP (php.ini)
- Find words ;extension=sodium
- Remove the semicolon ;
- Save and restart XAMPP again

## Project setup
```
composer install
```

## Create new database in phpMyadmin
- Open phpMyAdmin via http://localhost/phpmyadmin
- Click New on the left sidebar
- Type the Database name: Medical_Station
- Click Create

## Create tables in database
- Open terminal of backend folder 
- Run: php artisan migrate

### Compiles and hot-reloads for development
```
php artisan serve
php -S localhost:8000 -t public
```