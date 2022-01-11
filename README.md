# Fast Controller

This console command helps to fast and easy create controllers and routes.
The command creates controllers on a single responsibility principle. Created class implements abstract method ``handle``

In this method, you can validate input data:
``$this->prepare($request);``

##installation
``composer require --dev dminustin/fast-controller``

then,

``php artisan vendor:publish --provider "FastController\FastControllerServiceProvider"``

It will copy config file, base controller and views

##Configuration
Change config/fast-controller.php file

##Usage
php artisan fast-controller:create [_path_]

##Example:
php artisan fast-controller:create articles/search

##Source
https://github.com/dminustin/fast-controller

##Contacts
Feel free to contact me at Github
