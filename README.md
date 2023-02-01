# Activity-Log
This package is use to save all activities of user build for NFS dashboard.


On your root of your project just run below command and download this DIR and paste in `lcw` folder. We dont have any package so you have to do it manually.
````
mkdir -p packages/lcw/activitylog
````


Simple use just add below line in your root composer.json in autoload seaction. Just append new line it must look like below.

````
"Lcw\\Activitylog\\": "packages/lcw/activitylog/src/"
````
The composer.json must be look like below after adding the like.

````
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/",
        "Lcw\\Activitylog\\": "packages/lcw/activitylog/src/"
    }
},
````

After add run the below command to load the package in your composer.

````
composer dump-autoload
````

One the composer run and recreated the files of packages run the below line to export the migrations and also add the service provider in your `config/app.php`.
Open file app.php and add below line under providers array, better to add in packages section.

````
/*
* Package Service Providers...
*/
...
...
Lcw\Activitylog\Providers\ActivityLogProvider::class,
````

For migration export run below command.

````
php artisan vendor:publish --provider="Learncodeweb\Activitylog\Providers\ActivityLogProvider" --tag="migrations"
````

After migration export/publish just run simple command to add table.

````
php artisan migrate
````

Thank you.

