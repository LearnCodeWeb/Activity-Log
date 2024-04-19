<?php

use Illuminate\Support\Facades\Route;
use Lcw\Activitylog\Controllers\ActivityLogController;

Route::middleware(['web','auth'])->group(function () {
    Route::get('log', [ActivityLogController::class, "index"])->name('lcw_activity_log_index');
});
