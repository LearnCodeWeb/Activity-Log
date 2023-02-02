# Activity Log
This package is use to save all activities of user build for NFS dashboard.


On the root of your project just run the below command and download this DIR and paste in activitylog folder. We don't have any package so you have to do it manually.

````
mkdir -p packages/lcw/activitylog
````

Simple use just adds the below line in your root `composer.json` in autoload section.

````
"Lcw\\Activitylog\\": "packages/lcw/activitylog/src/"
````

The `composer.json` must look like the below after adding the like.

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

After adding run the below command to load the package in your composer.

````
composer dump-autoload
````

After the composer add the service provider in your `config/app.php`. Open file `config/app.php` and add the below line under the provider's array, better to add it in the packages section.

````
/*
* Package Service Providers...
*/
...
...
Lcw\Activitylog\Providers\ActivityLogProvider::class,
````

For migration, export runs the below command.

````
php artisan vendor:publish --provider="Learncodeweb\Activitylog\Providers\ActivityLogProvider" --tag="migrations"
````

After migration `publishes` just run a simple command to add a table.

````
php artisan migrate
````

There is a default view to see the logs but you can made your own log view in your app. With this package one route will be created which is mentioned below.

````
# Route Name
lcw_activity_log_index

# URI
Your-Domain-Url/log
````

You can create your own view and get the log data by using the below method in your controller.

````
use Lcw\Activitylog\ActivityLog;

$activityLog = new ActivityLog();
$log = $activityLog->get($request);
````

You can also delete the data and change the default limit by defining the variable in your environment.
Pass the value in months default is set at `3 Months`. Open the `.env` file and define the below variable to change the default auto-delete process.

````
# Define in months only

ACTIVITY_LOG_DEL=1 
````

And you also can delete the log by calling the `logDelete` method like below in your controller.

````
use Lcw\Activitylog\ActivityLog;

$activityLog = new ActivityLog();
$log = $activityLog->logDelete(1); # delete all one month old log data
````

If you need any help ask me, Thank you.
