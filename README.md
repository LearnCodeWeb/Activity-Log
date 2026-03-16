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

> ⚠️ **Important:** This package does **not** support Laravel's auto-discovery. You **must** manually register the service provider based on your Laravel version.

---

#### Laravel 8, 9, 10 — `config/app.php`

Open `config/app.php` and add inside the `providers` array:

```php
/*
 * Package Service Providers...
 */
Lcw\Activitylog\Providers\ActivityLogProvider::class,
```

---

#### Laravel 11 & 12 — `bootstrap/providers.php`

Laravel 11 and 12 **no longer use** `config/app.php` for providers. Instead, open `bootstrap/providers.php` and add:

```php
<?php

return [
    App\Providers\AppServiceProvider::class,
    Lcw\Activitylog\Providers\ActivityLogProvider::class, // ✅ Add this line
];
```

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

## 🏢 Multi-Tenant Setup (stancl/tenancy)

Agar aap `stancl/tenancy` (tenancy-for-laravel) use kar rahe hain toh neeche diye steps follow karo. Is package ki migration **tenant database** mein hogi aur har tenant apna alag activity log rakhega.

---

### Step 1 — Migration Publish Karke Tenant Folder Mein Move Karo

`stancl/tenancy` mein tenant migrations `database/migrations/tenant/` folder mein honi chahiye.

```bash
# Pehle publish karo
php artisan vendor:publish --provider="Lcw\Activitylog\Providers\ActivityLogProvider" --tag="migrations"

# Phir tenant folder mein move karo
mv database/migrations/*_create_activity_log_master.php database/migrations/tenant/
```

---

### Step 2 — Tenancy Config Mein Migration Path Confirm Karo

`config/tenancy.php` mein check karo ke tenant migrations ka path sahi set hai:

```php
'migration_parameters' => [
    '--path'     => ['/database/migrations/tenant'],
    '--realpath' => true,
    '--force'    => true,
],
```

---

### Step 3 — Tenant Migration Run Karo

```bash
# Sab tenants par migrate karo
php artisan tenants:migrate

# Sirf ek specific tenant par migrate karo
php artisan tenants:migrate --tenants=your-tenant-id
```

Yeh command **har tenant ke alag database** mein `activity_log_master` table create kar degi.

---

### Step 4 — Naye Tenant Par Auto Migration

Jab naya tenant create ho toh automatically migration run ho — `app/Providers/TenancyServiceProvider.php` mein confirm karo ke yeh configured hai:

```php
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Jobs\MigrateDatabase;

'listeners' => [
    Events\TenantCreated::class => [
        MigrateDatabase::class, // ✅ Naye tenant par auto migrate hoga
    ],
],
```

Agar yeh set hai toh har naye tenant ke create hone par `activity_log_master` table automatically ban jayega.

---

### Step 5 — Tenant Context Mein Normal Use Karo

`stancl/tenancy` automatically tenant ka database switch karta hai. Package bilkul normal tarah use karo — har tenant ka data automatically uske apne database mein jayega:

```php
use Lcw\Activitylog\ActivityLog;

$activityLog = new ActivityLog();

// Logs fetch karo (current tenant ka database use hoga automatically)
$logs = $activityLog->get($request);

// Custom log banana
$activityLog->create([
    'log' => 'User ne invoice download ki',
]);

// Purane logs delete karo
$activityLog->logDelete(3);
```

---

### Step 6 — Central App Se Specific Tenant Ka Log Dekhna

Agar aap central panel se kisi specific tenant ka log access karna chahte hain:

```php
use Lcw\Activitylog\ActivityLog;

$tenant = \App\Models\Tenant::find('tenant-id');

tenancy()->initialize($tenant);

$activityLog = new ActivityLog();
$logs = $activityLog->get($request);

tenancy()->end();

return view('admin.tenant-logs', compact('logs'));
```

---

### Tenant Default Log View

Tenant subdomain ya domain par default log view is URL par milega:

```
http://tenant1.yourdomain.com/log
```

Har tenant ka `/log` sirf **usi tenant ka data** dikhayega.

---

### Tenant Setup — Quick Reference

| Task | Command |
|------|---------|
| Migration publish karo | `php artisan vendor:publish --tag="migrations"` |
| Migration move karo | `database/migrations/tenant/` folder mein |
| Sab tenants migrate karo | `php artisan tenants:migrate` |
| Single tenant migrate karo | `php artisan tenants:migrate --tenants=id` |
| Tenant rollback karo | `php artisan tenants:migrate --rollback` |
| Config publish karo | `php artisan vendor:publish --tag="config"` |

---

## ❓ FAQ

**Q: Does it log guest (unauthenticated) users?**  
A: No, this package is designed for logged-in users only. It works best inside admin/user panels after authentication.

**Q: Can I use my own view instead of the default `/log` page?**  
A: Yes! Use `$activityLog->get($request)` in your controller and pass the data to your own Blade view.

**Q: How do I stop certain pages from being logged?**  
A: Publish the config file and add those route names or URIs to the `ignore_routes` array.

**Q: Does it work with Laravel 12?**  
A: Yes, but you must manually register the provider in `bootstrap/providers.php` — see the [Register Service Provider](#register-service-provider) section above.

**Q: Does it support multi-tenancy with stancl/tenancy?**  
A: Yes! Move the published migration to `database/migrations/tenant/`, run `php artisan tenants:migrate`, and the package will work per-tenant automatically. See the [Multi-Tenant Setup](#-multi-tenant-setup-stancltenancy) section for full details.

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
🌐 [quran.ahlesunat.com](https://quran.ahlesunat.com)  
📧 zaidbinkhalid31@gmail.com  
📦 [Packagist](https://packagist.org/packages/learncodeweb/activitylog) | [GitHub](https://github.com/LearnCodeWeb/Activity-Log)

---

> If this package helped you, please consider giving it a ⭐ on GitHub!
