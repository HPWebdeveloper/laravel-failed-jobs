<?php

namespace HPWebdeveloper\LaravelFailedJobs\Http\Controllers;

use Illuminate\Support\Facades\App;
use HPWebdeveloper\LaravelFailedJobs\FailedJobs;

class HomeController extends Controller
{
    /**
     * Single page application catch-all route.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('failedjobs::layout', [
            'assetsAreCurrent' => FailedJobs::assetsAreCurrent(),
            'failedJobsScriptVariables' => FailedJobs::scriptVariables(),
            'isDownForMaintenance' => App::isDownForMaintenance(),
        ]);
    }
}
