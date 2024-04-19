<?php

/**
 * The basic config file that manage
 * Activity main actions
 * There are only few settings we need in activity log 
 * 
 * 
 * Main settings are listed below you can customize them after publishing the config
 * 
 */

return [
    'lcw_activity_log' => [
        /**
         * Change the value to change the activity log delete limit
         * By default it is 3 in months
         * @param integer in months
         */
        'delete_limit' => 3,

        /**
         * Pass route path or names
         * If you don't want to create the activity log use ignore_routes 
         * @param route URI/NAMES
         */
        'ignore_routes' => [],
    ]
];
