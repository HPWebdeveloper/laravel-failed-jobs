<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | FailedJobs Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where FailedJobs will be accessible from. If this
    | setting is null, FailedJobs will reside under the same domain as the
    | application. Otherwise, this value will serve as the subdomain.
    |
    */

    'domain' => env('FAILEDJOBS_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | FailedJobs Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where FailedJobs will be accessible from. Feel free
    | to change this path to anything you like. Note that the URI will not
    | affect the paths of its internal API that aren't exposed to users.
    |
    */

    'path' => env('FAILEDJOBS_PATH', 'failedjobs'),

    /*
    |--------------------------------------------------------------------------
    | FailedJobs Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will get attached onto each FailedJobs route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    */

    'middleware' => ['web'],



    'axios_base_url' => env('AXIOS_BASE_URL', ''),

    'server_access_token' => env('FAILEDJOBS_SERVER_ACCESS_TOKEN'),

    'dashboard_access_token' => env('FAILEDJOBS_DASHBOARD_ACCESS_TOKEN'),


];
