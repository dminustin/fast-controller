# Fast Controller

##Configuration
Change config/fast-controller.php file

##usage
php artisan fast-controller:create [_path_]

###Example:
php artisan fast-controller:create articles/search

Will be added a controller controller_path/Articles/ArticlesSearchController
default controller_path is Http/Controllers/Generated/

Additionally, will be added router path, see routes/fc-routes.php 

