<?php

namespace Lcw\Activitylog\Controllers;

use Exception;
use Illuminate\Http\Request;
use Lcw\Activitylog\ActivityLog;

class ActivityLogController
{
    /**
     * Get all logs
     * @param request paramerters and model
     * @return view
     */
    public function index(Request $request, ActivityLog $activityLog)
    {
        try {
            $user = $activityLog->getUsers();
            $log = $activityLog->get($request);
            $data = [
                'log' => $log,
                'user' => $user,
            ];
            return view('ActivityLog::index', $data);
        } catch (Exception $e) {
            return '[Controller Index] Fetch data not working: ' . $e->getMessage();
        }
    }
}