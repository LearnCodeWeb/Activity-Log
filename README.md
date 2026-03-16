# 🗂️ Laravel Activity Log

[![Latest Version on Packagist](https://img.shields.io/packagist/v/learncodeweb/activitylog.svg?style=flat-square)](https://packagist.org/packages/learncodeweb/activitylog)
[![Total Downloads](https://img.shields.io/packagist/dt/learncodeweb/activitylog.svg?style=flat-square)](https://packagist.org/packages/learncodeweb/activitylog)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.0-blue.svg)](https://php.net)

A simple and powerful **Activity Log** package for Laravel. It automatically tracks and records every user action in your admin or user panel after login — no complex setup needed.

> ✅ Tested with **Laravel 8, 9, 10, 11, 12**

---

## ✨ Features

- 🔍 Automatically logs all authenticated user activity
- 🚫 Ignore specific routes you don't want to track
- 🗑️ Auto-delete old logs based on configurable time limit
- 📋 Built-in default view to see all logs at `/log`
- 🛠️ Create custom logs manually with full control
- ⚡ Easy installation via Composer

---

## 📋 Requirements

| Requirement | Version |
|-------------|---------|
| PHP | >= 7.0 |
| Laravel | >= 8.x |
| Guzzle HTTP | ^7.0 |

---

## 🚀 Installation

Install the package via Composer:

```bash
composer require learncodeweb/activitylog
```

### Register Service Provider

**Laravel 8 – 10:** Add the service provider in `config/app.php` under the `providers` array:

```php
/*
 * Package Service Providers...
 */
Lcw\Activitylog\Providers\ActivityLogProvider::class,
```

> **Note:** Laravel 11+ uses auto-discovery — no manual registration needed.

---

## 🗄️ Database Setup

### Step 1 — Publish Migration

```bash
php artisan vendor:publish --provider="Lcw\Activitylog\Providers\ActivityLogProvider" --tag="migrations"
```

### Step 2 — Run Migration

```bash
php artisan migrate
```

This creates the `activity_logs` table in your database.

---

## ⚙️ Configuration (Optional)

Export the config file to customize settings:

```bash
php artisan vendor:publish --provider="Lcw\Activitylog\Providers\ActivityLogProvider" --tag="config"
```

This creates `config/lcw_activity_log_config.php`. Below are the available options:

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Delete Limit
    |--------------------------------------------------------------------------
    | Set how many months of old logs to keep before auto-deletion.
    | Default: 3 months
    */
    'delete_limit' => 3,

    /*
    |--------------------------------------------------------------------------
    | Ignore Routes
    |--------------------------------------------------------------------------
    | List route names or URIs where you do NOT want activity to be logged.
    | Example: dashboard index, file downloads, etc.
    */
    'ignore_routes' => [
        'dashboard.index',
        'settings.download.history.activity_log',
    ],

];
```

---

## 📖 Usage

### View Logs (Default Built-in View)

The package automatically provides a log viewer at:

```
http://your-domain.com/log
```

**Route name:** `lcw_activity_log_index`

---

### Get Logs in Your Own Controller

You can fetch logs manually and display them in your own view:

```php
use Lcw\Activitylog\ActivityLog;

public function index(Request $request)
{
    $activityLog = new ActivityLog();
    $logs = $activityLog->get($request);

    return view('your-view', compact('logs'));
}
```

---

### Delete Old Logs

Delete logs older than a given number of months:

```php
use Lcw\Activitylog\ActivityLog;

$activityLog = new ActivityLog();

// Delete logs older than 1 month
$activityLog->logDelete(1);

// Delete logs older than 3 months
$activityLog->logDelete(3);
```

---

### Create a Custom Log Entry

You can manually create a log entry with custom data:

```php
use Lcw\Activitylog\ActivityLog;

$activityLog = new ActivityLog();

$activityLog->create([
    'log'          => 'User updated their notification settings',
    'query_string' => $request->all(),
]);
```

#### Available Parameters for `create()`

| Parameter | Type | Description |
|-----------|------|-------------|
| `log` | `string` | A description of the activity |
| `server_ip` | `string` | The server's IP address |
| `user_ip` | `string` | The client/user's IP address |
| `route_detail` | `array` | Array with current route path details |
| `query_string` | `array` | Array of request parameters |
| `user_id` | `int` | Authenticated user's ID |
| `user` | `array` | Array of authenticated user data |
| `created_at` | `datetime` | Custom timestamp for the log entry |

---

## 🔒 Ignoring Routes

To skip logging on specific routes, publish the config (see above) and add route **names** or **URIs** to the `ignore_routes` array:

```php
'ignore_routes' => [
    'dashboard.index',           // by route name
    'api/health-check',          // by URI
    'settings.download.history.activity_log',
],
```

---

## 📁 Package Structure

```
src/
├── ActivityLog.php              # Core activity log class
├── Providers/
│   └── ActivityLogProvider.php  # Service provider
├── Http/
│   └── Middleware/              # Auto-logging middleware
├── Models/
│   └── ActivityLogModel.php     # Eloquent model
├── database/
│   └── migrations/              # Migration files
├── config/
│   └── lcw_activity_log_config.php  # Config file
└── resources/
    └── views/                   # Default log viewer blade templates
```

---

## 🧪 Example: Full Controller Usage

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Lcw\Activitylog\ActivityLog;

class ActivityLogController extends Controller
{
    protected $activityLog;

    public function __construct()
    {
        $this->activityLog = new ActivityLog();
    }

    // Show all activity logs
    public function index(Request $request)
    {
        $logs = $this->activityLog->get($request);
        return view('admin.activity-log', compact('logs'));
    }

    // Delete logs older than X months
    public function deleteOld(Request $request)
    {
        $months = $request->input('months', 3);
        $this->activityLog->logDelete($months);

        return back()->with('success', 'Old logs deleted successfully!');
    }

    // Manually log a custom action
    public function logCustomAction()
    {
        $this->activityLog->create([
            'log' => 'Admin exported the user report',
        ]);

        return back()->with('success', 'Action logged!');
    }
}
```

---

## ❓ FAQ

**Q: Does it log guest (unauthenticated) users?**  
A: No, this package is designed for logged-in users only. It works best inside admin/user panels after authentication.

**Q: Can I use my own view instead of the default `/log` page?**  
A: Yes! Use `$activityLog->get($request)` in your controller and pass the data to your own Blade view.

**Q: How do I stop certain pages from being logged?**  
A: Publish the config file and add those route names or URIs to the `ignore_routes` array.

**Q: Does it work with Laravel 12?**  
A: Yes, it is compatible with Laravel 8 through 12.

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!  
Feel free to open an [issue](https://github.com/LearnCodeWeb/Activity-Log/issues) or submit a pull request.

---

## 📄 License

This package is open-sourced software licensed under the [MIT License](LICENSE).

---

## 👨‍💻 Author

**Mian Zaid (Khalid Zaid)**  
🌐 [learncodeweb.com](https://learncodeweb.com)  
📧 contact.woop@learncodeweb.com  
📦 [Packagist](https://packagist.org/packages/learncodeweb/activitylog) | [GitHub](https://github.com/LearnCodeWeb/Activity-Log)

---

> If this package helped you, please consider giving it a ⭐ on GitHub!
