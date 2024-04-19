<?php

namespace Lcw\Activitylog;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Lcw\Activitylog\Models\ActivityLog as ModelsActivityLog;
use Lcw\Activitylog\Traits\ActivityLogActions;


/**
 * This package will save the log in activity log master table
 * You can pass @param listed below
 * 
 * Default Route is Your-Domain/log
 * 
 * @param ignore_route [If set system ignore to create the activity on that route]
 * @param log [string]
 * @param server_ip [The server ip address]
 * @param user_ip [The client/user ip address]
 * @param route_detail [Array with route path deatils]
 * @param query_string [Array with parameters]
 * @param user_id [Auth session id]
 * @param user [Array of auth]
 * @param created_at [datetime]
 */

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

            $limit = isset($request->limit) ? $request->limit : '10';

            // Delete all old Records
            self::logDelete();
            $activityLog = new ModelsActivityLog();
            if (!empty($request->from_created_at)) {
                $activityLog = $activityLog->where(DB::raw('DATE(created_at)'), '>=', $request->from_created_at);
            }
            if (!empty($request->to_created_at)) {
                $activityLog = $activityLog->where(DB::raw('DATE(created_at)'), '<=', $request->to_created_at);
            }
            if (!empty($request->log)) {
                $activityLog = $activityLog->where('log', 'LIKE', '%' . $request->log . '%');
            }
            if (!empty($request->user_id)) {
                $activityLog = $activityLog->where('user_id', $request->user_id);
            }


            return $activityLog->orderBy('id', 'DESC')->paginate($limit);
        } catch (Exception $e) {
            return '[Get Method] Fetch data not working: ' . $e->getMessage();
        }
    }


    /**
     * Get user name with id from auth table
     * @param pass user auth id
     * @return array
     */
    public function getUsers(array $parameters = [])
    {
        $activityLog = new ModelsActivityLog();
        $allUsers = $activityLog->select('user')->get();
        $aData = [];
        foreach ($allUsers as $key => $value) {
            $user = json_decode($value['user'], true);
            if (!empty($user))
                if (array_key_exists('username', $user)) {
                    $aData[$user['id']] = $user['username'];
                } elseif (array_key_exists('name', $user)) {
                    $aData[$user['id']] = $user['name'];
                } elseif (array_key_exists('firstname', $user)) {
                    $aData[$user['id']] = $user['firstname'];
                } elseif (array_key_exists('lastname', $user)) {
                    $aData[$user['id']] = $user['lastname'];
                } elseif (array_key_exists('first_name', $user)) {
                    $aData[$user['id']] = $user['first_name'];
                } elseif (array_key_exists('last_name', $user)) {
                    $aData[$user['id']] = $user['last_name'];
                } else {
                    $aData[$user['id']] = $user['id'];
                }
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

        // Get the Server IP details
        $serverIpGeo = $this->getGeoDetailByIP($server_ip);
        if (empty($serverIpGeo)) {
            $serverIpGeo =  json_encode(['server ip' => $server_ip]);
        }
        $server_ip_detail =  json_encode($serverIpGeo);

        // Get the User IP details
        $userIpGeo = $this->getGeoDetailByIP($user_ip);
        if (empty($userIpGeo)) {
            $userIpGeo =  json_encode(['server ip' => $server_ip]);
        }
        $user_ip_detail =  json_encode($userIpGeo);

        $route_detail = $this->routeDetail();
        $query_string = $this->queryString();
        $user_id = $this->userData('id');
        $user = $this->userData('data');
        $created_at = date('Y-m-d H:i:s');

        $data = [
            'log' => $log,
            'server_ip_detail' => $server_ip_detail,
            'user_ip_detail' => $user_ip_detail,
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
     * If ignore route set system will not create any activity on that route
     * @param parameters array
     * @return resources with created log
     */
    public function create(array $paramerts = [])
    {

        $configSettings = $this->getConfigSettings();
        if (isset($configSettings['ignore_routes']) && !empty($configSettings['ignore_routes'])) {
            $routeName =  Route::currentRouteName();
            $routePath = Route::getFacadeRoot()->current()->uri();
            if (in_array($routeName, $configSettings['ignore_routes'])) {
                return true;
            }
            if (in_array($routePath, $configSettings['ignore_routes'])) {
                return true;
            }
        }

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
    }


    /**
     * Delete older data 
     * Set [delete_limit] in config file.
     * @param pass any number in months
     * @return bool
     */
    public function logDelete(int $month = 3)
    {
        try {
            if (!isset($month)) {
                $configSettings = $this->getConfigSettings();
                $month = $configSettings['delete_limit'];
            }
            $oldDate = date('Y-m-d', strtotime('-' . $month . ' months'));

            $activityLog = new ModelsActivityLog();
            return $activityLog->where(DB::raw('DATE(created_at)'), '<', $oldDate)->delete();
        } catch (Exception $e) {
            return '[Delete Method] log delete not working: ' . $e->getMessage();
        }
    }
}
