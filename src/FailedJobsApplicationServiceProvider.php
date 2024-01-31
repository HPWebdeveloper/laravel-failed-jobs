<?php

namespace HPWebdeveloper\LaravelFailedJobs;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class FailedJobsApplicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->authorization();
    }

    /**
     * Configure the FailedJobs authorization services.
     *
     * @return void
     */
    protected function authorization()
    {
        $this->gate();

        FailedJobs::auth(function ($request) {
            return Gate::check('viewFailedJobs', [$request->user()]) || app()->environment('local');
        });
    }

    /**
     * Register the FailedJobs gate.
     *
     * This gate determines who can access FailedJobs in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewFailedJobs', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
