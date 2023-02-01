<?php

namespace Lcw\Activitylog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{


    use HasFactory;
    protected $table = 'activity_log_master';
    // Disable Laravel's mass assignment protection
    protected $guarded = [];
}