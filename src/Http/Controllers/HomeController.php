<?php

namespace HPWebdeveloper\LaravelFailedJobs\Http\Controllers;

use HPWebdeveloper\LaravelFailedJobs\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\App;
use HPWebdeveloper\LaravelFailedJobs\FailedJobs;
use Illuminate\Routing\Controller as BaseController;

class HomeController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    /**
     * Single page application catch-all route.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('failedjobs::layout', [
            'assetsAreCurrent' => FailedJobs::assetsAreCurrent(),
            'failedJobsScriptVariables' => array_merge(FailedJobs::scriptVariables(), [
                'axios_base_url' => config('failedjobs.axios_base_url'),
                'access_token' => config('failedjobs.dashboard_access_token'),
            ]),
            'isDownForMaintenance' => App::isDownForMaintenance(),
        ]);
    }
}
