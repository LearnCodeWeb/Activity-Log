<?php

namespace Lcw\Activitylog\Traits;

use Auth;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;


trait ActivityLogActions
{

    /**
     * Method to get the client/server IP address
     * @param user/server accept only these two agents
     * @return IP address either user/server
     */
    public function getIP(string $agent = "user")
    {
        if ($agent == "user") {
            foreach (array('HTTP_FORWARDED', 'HTTP_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'REMOTE_ADDR', 'HTTP_X_FORWARDED_FOR') as $key) {
                if (array_key_exists($key, $_SERVER) === true) {
                    foreach (explode(',', $_SERVER[$key]) as $ip) {
                        $ip = trim($ip); // remove space
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                            return $ip;
                        }
                    }
                }
            }
            $ip = request()->ip(); // it will return the server IP if the client IP is not found using this method.
        }
        if ($agent == "server") {
            $ip = request()->server('SERVER_ADDR') ? request()->server('SERVER_ADDR') : request()->ip();
        }
        return $ip;
    }



    /**
     * Get Geo detail of the IP
     * @param string ip address
     * @return response
     */
    public function getGeoDetailByIP($ip = "")
    {
        try {
            // This is the free ip info checker for big data maybe you need to pay
            $url = 'https://ipinfo.io/' . $ip . '/geo';
            $getGeoDetail = Http::get($url);
            if ($getGeoDetail->successful()) {
                return $getGeoDetail->json();
            }
        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * Accepting user id only
     * @param only user id need to pass
     * @return id
     */
    public function userData(string $action = "id")
    {
        if ($action == "id") {
            $response = 0;
            $response = Auth::id() ? Auth::id() : 0;
        }
        if ($action == "data") {
            $response = "";
            if (Auth::check()) {
                $response = json_encode(Auth::user()->toArray());
            }
        }
        return $response;
    }

    /**
     * Accepting Log string
     * @param string
     * @return string
     */
    public function logText(string $logText = "")
    {
        if (is_string($logText) && !empty($logText)) {
            $response =  $logText;
        } else {
            $response =  Route::getFacadeRoot()->current()->uri();
        }
        return $response;
    }


    /**
     * Accepting route resources array
     * @param array of detail about route deatil
     * @return string
     */
    public function routeDetail(array $routeDeatilArray = [])
    {
        if (!empty($routeDeatilArray)) {
            $response =  json_encode($routeDeatilArray);
        } else {
            $response = json_encode(Route::current()->action);
        }
        return $response;
    }

    /**
     * Accepting query string
     * @param array of query string
     * @return string
     */
    public function queryString(array $queryString = [])
    {
        if (!empty($queryString)) {
            $response =  json_encode($queryString);
        } else {
            $routeParams = request()->route()->parameters();
            $requestParams = request()->all();
            $response =  json_encode($routeParams + $requestParams);
        }
        return $response;
    }



    /**
     * Check the config settings if user set and publish
     * We will use user settings
     * @return array
     */
    public function getConfigSettings()
    {
        $configData = config('lcwActivityLogConfig.lcw_activity_log');
        if (file_exists(config_path('lcw_activity_log_config.php'))) {
            $configData = config('lcw_activity_log_config.lcw_activity_log');
        }
        return $configData;
    }


    /**
     * Covert nested to single array
     * @param array
     * @return array
     */
    public function singleArray($array)
    {
        $result = [];

        foreach ($array as $key => $value) {
            $currentKey = $key;

            if (is_array($value)) {
                $result = array_merge($result, $this->singleArray($value));
            } else {
                $result[$currentKey] = $value;
            }
        }

        return $result;
    }
}
