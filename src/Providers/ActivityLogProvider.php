<?php

namespace Lcw\Activitylog\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class ActivityLogProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'ActivityLog');

        Paginator::useBootstrap();

        if ($this->app->runningInConsole()) {
            // Export the migration
            if (!class_exists('CreateActivityLogMaster')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_activity_log_master.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_activity_log_master.php'),
                ], 'migrations');
            }
        }
    }
}