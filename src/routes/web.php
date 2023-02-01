<?php

use Illuminate\Support\Facades\Route;
use Learncodeweb\Activitylog\Controllers\ActivityLogController;

Route::middleware(['web'])->group(function () {
    Route::get('log', [ActivityLogController::class, "index"])->name('lcw_activity_log_index');
});