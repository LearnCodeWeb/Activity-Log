# Activity Log

This package is only for Laravel >=8 Framework.

<details>
  <summary>Manual installation process.</summary>
  
  On the root of your project just run the below command download this DIR and paste it into the activitylog folder. We don't have any package so you have to do it manually.

````
mkdir -p packages/lcw/activitylog
````
After copying, the code goes to that directory and updates the composer for that use below command.


````
cd packages/lcw/activitylog/
composer dump-autoload
````
Simple use Just add the below line in your root composer.json in the autoload section.


````
"Lcw\\Activitylog\\": "packages/lcw/activitylog/src/"
````
The composer.json must look like the below after adding the like.


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

</details>


## Composer Installation
Open `composer.json` and add the below line under the repositories array. If you have any other package then append this package. The must look like below.


````
"repositories": [
        ....,
        ....,
        ....,
        {
            "type": "vcs",
            "url": "https://github.com/kateoftokyo/Dashboard-Activity-Log"
        }
    ],
````
After adding the above code try to install the package in your project.


````
composer require lcw/activitylog
````

## Add Service
After the composer add the service provider in your `config/app.php`. Open file config/app.php and add the below line under the provider's array, better to add it in the packages section.
````
/*
* Package Service Providers...
*/
...
...
Lcw\Activitylog\Providers\ActivityLogProvider::class,
````

## Migration Export
For migration, export runs the below command.
````
php artisan vendor:publish --provider="Lcw\Activitylog\Providers\ActivityLogProvider" --tag="migrations"
````
After migration publishes just run a simple command to add a table.

````
php artisan migrate
````

## Config Export
You can also export the config file into your project root config, this function empowers you to change things if you want. With the config export, you can set the delete limit in your config file and also you can add route PATH/NAMES where you don't want to save activity.

To export the config on your root run the below command.
````
php artisan vendor:publish --provider="Lcw\Activitylog\Providers\ActivityLogProvider" --tag="config"
````
After `publish config` you can set the parameters as per your need. Below is the list of all available parameters.

````
/**
 * Change the value to change the activity log delete limit
 * By default it is 3 in months
 * @param integer in months
 */
'delete_limit' => 2,
/**
 * Pass route path or names
 * If you don't want to create the activity log use ignore_routes
 * @param route URI/NAMES
 */
'ignore_routes' => ['dashboard.index', 'settings.download.history.activity_log'],
````
Set config in settings ignore_route [If set system ignore to create the activity on that route]

You can also delete the data and change the default limit by editing the config file config\lcw_activity_log_config.php. Pass the value in months default is set at 3 Months.

# Define in months only
````
delete_limit = 2
````
Default View
There is a default view to see the logs but you can make your own log view in your app. With this package, one route will be created which is mentioned below.



# Route Name
````
lcw_activity_log_index
````
# URI
````
Your-Domain-Url/log
````
You can create your own view and get the log data by using the below method in your controller.

````
use Lcw\Activitylog\ActivityLog;
$activityLog = new ActivityLog();
$log = $activityLog->get($request);
````
You also can delete the log by calling the logDelete() method like below in your controller.
````
use Lcw\Activitylog\ActivityLog;
$activityLog = new ActivityLog();
$log = $activityLog->logDelete(1); # delete all one month old log data
````

## Custom Creation
You can create a custom activity log with the below method. The parameters you need to pass in the custom log are given below.
````
@param log [string]
@param server_ip [The server IP address]
@param user_ip [The client/user IP address]
@param route_detail [Array with route path details]
@param query_string [Array with parameters]
@param user_id [Auth session id]
@param user [Array of auth]
@param created_at [datetime]
````

````
use Lcw\Activitylog\ActivityLog;
$activityLog = new ActivityLog();
$activityLog->create(['log' => 'Update notification', 'query_string' => $parameters]);
````
If you need any help ask me, Thank you.
