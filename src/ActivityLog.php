<?php

namespace Lcw\Activitylog;

use Illuminate\Http\Request;
use Exception;
use Lcw\Activitylog\Models\ActivityLog as ModelsActivityLog;
use Lcw\Activitylog\Traits\ActivityLogActions;

class ActivityLog
{

    use ActivityLogActions;


    /**
     * Get all the log with 25 pagination
     * @param no need to pass
     * @return array with all details.
     */
    public function get(Request $request)
    {
        try {
            $condition = '1';
            if (!empty($request->from_created_at)) {
                $condition .= ' AND created_at >= "' . $request->from_created_at . '"';
            }
            if (!empty($request->to_created_at)) {
                $condition .= ' AND created_at <= "' . $request->to_created_at . '"';
            }
            if (!empty($request->log)) {
                $condition .= ' AND log LIKE "%' . $request->log . '%"';
            }
            if (!empty($request->user_id)) {
                $condition .= ' AND user_id = "' . $request->user_id . '"';
            }

            $activityLog = new ModelsActivityLog();
            return $activityLog->whereRaw($condition)->orderBy('id', 'DESC')->paginate(5);
        } catch (Exception $e) {
            return 'Fetch data not working: ' . $e->getMessage();
        }
    }



    public function getUsers()
    {
        $activityLog = new ModelsActivityLog();
        $allUsers = $activityLog->select('user')->get();
        $aData = [];
        foreach ($allUsers as $key => $value) {
            $user = json_decode($value['user'], true);
            $aData[$user['id']] = $user['name'];
        }
        asort($aData);
        return array_unique($aData);
    }



    /**
     * The default method if user didn't set anything this will work
     * @param no need to pass
     * @return array with all details.
     */
    public function defaultData()
    {
        $log = $this->logText();
        $server_ip = $this->getIP('server');
        $user_ip = $this->getIP('user');
        $route_detail = $this->routeDetail();
        $query_string = $this->queryString();
        $user_id = $this->userData('id');
        $user = $this->userData('data');
        $created_at = date('Y-m-d H:i:s');

        $data = [
            'log' => $log,
            'server_ip' => $server_ip,
            'user_ip' => $user_ip,
            'route_detail' => $route_detail,
            'query_string' => $query_string,
            'user_id' => $user_id,
            'user' => $user,
            'created_at' => $created_at,
        ];
        return $data;
    }



    /**
     * Save the log record in table
     * @param parameters array
     * @return resources with created log
     */
    public function create(array $paramerts = [])
    {
        $activityLog = new ModelsActivityLog();
        $paramerts = array_filter($paramerts);
        $defaultParams = self::defaultData();
        $paramerts = $paramerts + $defaultParams;


        // Convert all array to string
        foreach ($paramerts as $key => $item) {
            if (is_array($item)) {
                $paramerts[$key] = json_encode($item);
            }
        }
        return $activityLog->insert($paramerts);
        // return self::get(new Request());
    }
}