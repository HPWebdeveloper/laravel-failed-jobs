<?php

namespace Workbench\App\Providers;

use Illuminate\Support\Facades\Gate;
use HPWebdeveloper\LaravelFailedJobs\FailedJobs;
use HPWebdeveloper\LaravelFailedJobs\FailedJobsApplicationServiceProvider;

class FailedJobsServiceProvider extends FailedJobsApplicationServiceProvider
{

    /**
     * Register the FailedJobs gate.
     *
     * This gate determines who can access FailedJobs in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewFailedJobs', function ($user) {
            return true;
        });
    }
}
