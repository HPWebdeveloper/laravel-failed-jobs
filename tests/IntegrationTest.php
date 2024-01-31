<?php

namespace HPWebdeveloper\LaravelFailedJobs\Tests;

use Illuminate\Support\Facades\Redis;
use HPWebdeveloper\LaravelFailedJobs\FailedJobs;
use Orchestra\Testbench\TestCase;

abstract class IntegrationTest extends TestCase
{
    /**
     * Setup the test case.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Redis::flushall();
    }

    /**
     * Tear down the test case.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        FailedJobs::$authUsing = null;
    }

    /**
     * Get the service providers for the package.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['HPWebdeveloper\LaravelFailedJobs\FailedJobsServiceProvider'];
    }

}
