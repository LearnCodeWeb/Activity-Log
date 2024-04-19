<?php

namespace Lcw\Activitylog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    protected $table = 'activity_log_master';
    protected $fillable = [
        'id',
        'log',
        'server_ip_detail',
        'user_ip_detail',
        'route_detail',
        'query_string',
        'user_id',
        'user',
        'created_at',
        'updated_at',
    ];
}
